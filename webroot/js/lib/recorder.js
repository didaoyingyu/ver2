(function(window){

  var WORKER_PATH = 'js/lib/recorderWorker.js';

  //var encoderWorker = new Worker('js/mp3worker.js');


  var Recorder = function(source, cfg){
    var config = cfg || {};
    var bufferLen = config.bufferLen || 4096;
    this.context = source.context;
    this.node = this.context.createScriptProcessor(bufferLen, 2, 2);
    var worker = new Worker(config.workerPath || WORKER_PATH);
    worker.postMessage({
      command: 'init',
      config: {
        sampleRate: this.context.sampleRate
      }
    });
    var recording = false,
      currCallback;

    this.node.onaudioprocess = function(e){
      if (!recording) return;
      worker.postMessage({
        command: 'record',
        buffer: [
          e.inputBuffer.getChannelData(0),
          e.inputBuffer.getChannelData(1)
        ]
      });
    }

    this.configure = function(cfg){
      for (var prop in cfg){
        if (cfg.hasOwnProperty(prop)){
          config[prop] = cfg[prop];
        }
      }
    }

    this.record = function(){
      recording = true;
    }

    this.stop = function(){
      recording = false;
    }

    this.clear = function(){
      worker.postMessage({ command: 'clear' });
    }

    this.getBuffer = function(cb) {
      currCallback = cb || config.callback;
      worker.postMessage({ command: 'getBuffer' })
    }

    this.exportWAV = function(cb, type){
      currCallback = cb || config.callback;
      type = type || config.type || 'audio/wav';
      if (!currCallback) throw new Error('Callback not set');
      worker.postMessage({
        command: 'exportWAV',
        type: type
      });
    }



    function parseWav(wav) {

      function readInt(i, bytes) {
        var ret = 0,
          shft = 0;

        while (bytes) {
          ret += wav[i] << shft;
          shft += 8;
          i++;
          bytes--;
        }
        return ret;
      }
      if (readInt(20, 2) != 1) throw 'Invalid compression code, not PCM';
      if (readInt(22, 2) != 1) throw 'Invalid number of channels, not 1';
      return {
        sampleRate: readInt(24, 4),
        bitsPerSample: readInt(34, 2),
        samples: wav.subarray(44)
      };

  }



    worker.onmessage = function(e){
      //var blob = e.data;
      var data,arrayBuffer;
      var blob = e.data;




      var fileReader = new FileReader();


      fileReader.onload = function(){

        arrayBuffer = this.result;
        var buffer = new Uint8Array(arrayBuffer),




        data = parseWav(buffer);

        console.log("Converting to Mp3");


        console.log(data);


        worker.postMessage({
          command: 'init',
          config: {
            mode: 3,
            channels: 1,
            sampleRate: data.SampleRate,
            bitrate: data.bitsPerSample
          }
        });


        worker.onmessage = function(e){
          console.log(e);


        }
       /* encoderWorker.postMessage({ cmd: 'init', config:{
          mode : 3,
          channels:1,
          samplerate: data.sampleRate,
          bitrate: data.bitsPerSample,
        }});
        encoderWorker.postMessage({ cmd: 'encode', buf: Uint8ArrayToFloat32Array(data.samples)});
        encoderWorker.postMessage({ cmd: 'finish'});
        encoderWorker.onmessage = function(e) {
          console.log(e);
            if (e.data.cmd == 'data') {
              console.log("Done converting to Mp3");
              var mp3Blob = new Blob([new Uint8Array(e.data.buf)], {type: 'audio/mp3'});



              var reader = new FileReader();
              reader.onload = function(event){
                //uploadFiles(id, obj, type, event.target.result);
                
              };      
              reader.readAsDataURL(mp3Blob);



             // uploadAudio(mp3Blob, e.data.obj, e.data.id, e.data.rectype);
            }
        };*/





      }

      fileReader.readAsArrayBuffer(blob);


      currCallback(blob);
    }


    function uploadAudio(mp3Data){
      var reader = new FileReader();
      /*reader.onload = function(event){
        //uploadFiles(id, obj, type, event.target.result);
      };*/      
      reader.readAsDataURL(mp3Data);
    }


    function Uint8ArrayToFloat32Array(u8a){
      var f32Buffer = new Float32Array(u8a.length);
      for (var i = 0; i < u8a.length; i++) {
        var value = u8a[i<<1] + (u8a[(i<<1)+1]<<8);
        if (value >= 0x8000) value |= ~0x7FFF;
        f32Buffer[i] = value / 0x8000;
      }
      return f32Buffer;
    }
    source.connect(this.node);
    this.node.connect(this.context.destination);    //this should not be necessary
  };

  Recorder.forceDownload = function(blob, filename){
    var url = (window.URL || window.webkitURL).createObjectURL(blob);
    var link = window.document.createElement('a');
    link.href = url;
    link.download = filename || 'output.wav';
    var click = document.createEvent("Event");
    click.initEvent("click", true, true);
    link.dispatchEvent(click);
  }

  window.Recorder = Recorder;

})(window);

var lock = 0;
var addCardProfile = 1;
var paglock = 0;
var globalStopCounter = 0;
var globalTimer = 0;
var soundValue = 0;
var repeating = 0;

var recordChecking = 0;



var dynamicAudio;



var gameMode = 'quick';
var globalInterval = 0;
var realTime = 0;
var cardsQueue = [];
var firstUse = 0;
var newCardsQueue = [];
var stringTimer = '';
var soundPath = '';
var cardX = 0;

var delayPlay = 2000;
var answerSwitch = 0;
var nextSwitch = 0;

var curCardX = 0;


var audio_context,
	globalAudioElement,
    recorder,
    volume,
    volumeLevel = 0,
    currentEditedSoundIndex,
    tdContainer,
    curBtn,
    globalUrl,
    globalStop = [];


   var theholder = {};




function addClassNow(e){
	lock = 1;
	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/viewAddClass").done(
		function( data ){
			lock = 0;
			jQuery('#dashboard-main-content-area').html(data);
		}
	);

}






function addStudentNow(e){
	lock = 1;
	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/viewAddStudent").done(
		function( data ){
			lock = 0;
			jQuery('#dashboard-main-content-area').html(data);
		}
	);

}



function addTeacherNow(e){
	lock = 1;
	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/viewAddTeacher").done(
		function( data ){
			lock = 0;
			jQuery('#dashboard-main-content-area').html(data);
		}
	);

}

function addClassNowFilter(e,userid){
	lock = 1;
	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');

	var userid = parseInt(userid);


	var datas = {'user_id' : userid};



	jQuery.ajax({
	    url: '/fcard/users/viewAddClassFilter',
	    data: {'datas' : datas},
	    type: 'GET',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	    	lock = 0;
			jQuery('#dashboard-main-content-area').html(data.responseText);
	    }
	});


}


function addDeckNowFilter(e,userid){
	lock = 1;
	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');

	var userid = parseInt(userid);


	var datas = {'user_id' : userid};



	jQuery.ajax({
	    url: '/fcard/users/viewAddDeckFilter',
	    data: {'datas' : datas},
	    type: 'GET',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	    	lock = 0;
			jQuery('#dashboard-main-content-area').html(data.responseText);
	    }
	});


}


function addDeckNow(e){
	lock = 1;
	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/viewAddDeck").done(
		function( data ){
			lock = 0;
			jQuery('#dashboard-main-content-area').html(data);
		}
	);

}

/* start record functions */

function startRecording(button,type,cardid) {
  recorder && recorder.record();
  button.disabled = true;


  var par = button.parentNode;
  if(par.getElementsByTagName('audio').length>0){
  	var remAudio = par.getElementsByTagName('audio')[0];
  	par.removeChild(remAudio);
  }


  button.nextElementSibling.disabled = false;
  button.nextElementSibling.innerHTML = 'Stop';
  button.nextElementSibling.onclick = function(e){
  	stopRecording(this,type,cardid);
  }
  
  console.log('Recording...');
}

function uploadFiles(dataHere,obj){

	var datas = {'file': dataHere,'type' : obj.type,'cardid' : parseInt(obj.cardid)};


	jQuery.ajax({
	    url: '/fcard/users/uploadSound',
	    data: {'datas' : datas},
	    type: 'POST',
	    dataType: 'json',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	    	recordChecking = 0;
	        //lock = 0;
	        var datos = data.responseJSON;
	        if(datos['status']=='ok'){

	        	globalStop[globalStopCounter].disabled = false;
	        	globalStop[globalStopCounter].innerHTML = 'Play';
				/*globalStop.onclick = function(e){
				  globalAudioElement.play();
				}*/
	        	globalStop[globalStopCounter].previousElementSibling.disabled = false;
	        	globalStopCounter++;
	        }else{
	        	globalStop[globalStopCounter].disabled = true;
	        	globalStop[globalStopCounter].innerHTML = 'Stop';
	        	globalStop[globalStopCounter].previousElementSibling.disabled = false;
	        	console.log('Error on uploading');
	        	globalStopCounter++;

	        }
	    }
	});
	return false;
}







function stopRecording(button,type,cardid) {

	if(button.innerHTML=='Play'){

		var itParent = button.parentNode;


		if(itParent.getElementsByTagName('audio').length>0){
			var audio = document.createElement('audio');
			audio.controls = true;
			audio.src = itParent.getElementsByTagName('audio')[0].src;
			audio.play();
		}else{

			if(recordChecking==0){
				recordChecking = 1;
				recorder && recorder.stop();
			  button.disabled = true;
			  //button.previousElementSibling.disabled = false;
			  console.log('Stopped recording.');
			  tdContainer = button.parentNode;
			  curBtn = button;
			  button.innerHTML = 'Encoding...';
			  theholder = {type : type, cardid: cardid};
			  // create WAV download link using audio data blob
			  //createDownloadLink();
			  createDownloadLink(theholder);
			  
			  recorder.clear();
			}
		  
		}

	}else{
	  recorder && recorder.stop();
	  button.disabled = true;
	  //button.previousElementSibling.disabled = false;
	  console.log('Stopped recording.');
	  tdContainer = button.parentNode;
	  curBtn = button;
	  button.innerHTML = 'Encoding...';
	  theholder = {type : type, cardid: cardid};
	  // create WAV download link using audio data blob
	  //createDownloadLink();
	  createDownloadLink(theholder);
	  
	  recorder.clear();


	}


 


}

function createDownloadLink(obj) {
  currentEditedSoundIndex = -1;
  recorder && recorder.exportWAV(handleWAV.bind(this),'audio/wav',obj);
}


function handleWAV(blob) {


  var appendHere = tdContainer;
  var audioElement = document.createElement('audio');
  var downloadAnchor = document.createElement('a');

  var url = URL.createObjectURL(blob);
  globalUrl = url;
  //var editButton = document.createElement('button');
  audioElement.controls = true;
  audioElement.src = url;
  globalAudioElement = audioElement;
  downloadAnchor.href = url;
  downloadAnchor.download = new Date().toISOString() + '.wav';
  downloadAnchor.innerHTML = 'Download';
  downloadAnchor.className = 'btn btn-primary';
  

  var stop = tdContainer.getElementsByTagName('button')[1];
  //stop.innerHTML = 'Play';
  stop.src = url;

 globalStop[globalStopCounter] = stop;
  //stop.disabled = false;
  stop.onclick = function(e){
    audioElement.play();
  }
   


}


function startUserMedia(stream) {
  var input = audio_context.createMediaStreamSource(stream);
  console.log('Media stream created.');

  volume = audio_context.createGain();
  volume.gain.value = volumeLevel;
  input.connect(volume);
  volume.connect(audio_context.destination);
  console.log('Input connected to audio context destination.');
  
  recorder = new Recorder(input);
  console.log('Recorder initialised.');
}

window.onload = function init() {
  try {
    // webkit shim
    window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext;
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    window.URL = window.URL || window.webkitURL || window.mozURL;
    
    audio_context = new AudioContext();
    console.log('Audio context set up.');
    console.log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
  } catch (e) {
    console.warn('No web audio support in this browser!');
  }
  
  navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
    console.warn('No live audio input: ' + e);
  });
};





/* end recording functions */

function getNextCard(cardHere){

	nextSwitch = 1;
	answerSwitch = 0;
	var countHere = cardsQueue.length - 1;

	dynamicAudio.src = '';
	if(typeof dynamicAudio!='undefined'){
		if(dynamicAudio.hasAttribute('loop')==true){
			dynamicAudio.removeAttribute('loop');
		}
	}
	if(curCardX<=countHere){




		if(cardHere[curCardX]['c']['question_sound_slow']!=''){




			if(cardHere[curCardX]['u']['sound']==1){
				dynamicAudio.controls = true;
				dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_slow'];
				dynamicAudio.play();
			}



			if(cardHere[curCardX]['u']['sound']==1){
				jQuery(dynamicAudio).bind('ended', function(){
					if(cardHere[curCardX]['c']['question_sound_fast']!=''){
						dynamicAudio.controls = true;
						dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_fast'];
						dynamicAudio.setAttribute('loop','');
						setTimeout(function(){
							dynamicAudio.play();
							/*var si = setInterval(function(){
								if(answerSwitch==0){
									dynamicAudio.play();
								}else{
									clearInterval(si);
								}
								
							},600);*/
							


						},delayPlay);
			
					}

				});
			}
			


		}else{
			if(cardHere[curCardX]['c']['question_sound_fast']!=''){
				if(cardHere[curCardX]['u']['sound']==1){
					if(dynamicAudio.hasAttribute('loop')==true){
						dynamicAudio.removeAttribute('loop');
					}
					dynamicAudio.controls = true;
					dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_fast'];
					dynamicAudio.setAttribute('loop','');
					setTimeout(function(){
						dynamicAudio.play();

						/*var si = setInterval(function(){
							if(answerSwitch==0){
								dynamicAudio.play();
							}else{
								clearInterval(si);
							}
							
						},600);*/
						


					},delayPlay);
				}
		
			}
		}


		/*if(cardHere[curCardX]['c']['question_sound_fast']!=''){
			dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_fast'];
				
			setTimeout(function(){

				while(answerSwitch=0){
					dynamicAudio.play();
				}


			},delayPlay);
		
		}*/



		/*if(cardHere[curCardX]['srs']==0){
			var frontCardContent = '<div class="game_question">'+cardHere[curCardX]['c']['question']+'</div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerAlreadyKnow" value="Already Know" /><input type="button" class="btn btn-primary answerLogicalGuess" value="Logical Guess" /><input type="button" class="btn btn-primary answerDontKnow" value="Don\'t know" /></div>';
		}else{
			var frontCardContent = '<div class="game_question">'+cardHere[curCardX]['c']['question']+'</div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerQuestion" value="Answer" /><input type="button" class="btn btn-primary finishGame" value="Finish"></div>';
		}*/


		if(gameMode=='quick'){
			var frontCardContent = '<div class="game_question"><div style="font-size:30px;">'+cardHere[curCardX]['c']['question']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['question_notes']+'</div></div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerQuestion" value="Answer" /><input type="button" class="btn btn-primary finishGame" value="Finish"></div>';

			if(cardHere[curCardX]['srs']==0){
				var backCardContent = '<div class="game_answer"><div style="font-size:30px;">'+cardHere[curCardX]['c']['answer']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['answer_notes']+'</div></div><div class="game_answer_options" style="text-align: center;"><input type="button" class="btn btn-primary answerAlreadyKnow" value="Already Know" /><input type="button" class="btn btn-primary answerLogicalGuess" value="Logical Guess" /><input type="button" class="btn btn-primary answerDontKnow" value="Don\'t know" /></div>';

			}else{
				var backCardContent = '<div class="game_answer"><div style="font-size:30px;">'+cardHere[curCardX]['c']['answer']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['answer_notes']+'</div></div><div class="game_answer_options" style="text-align: center;"><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-primary col-md-2 singleTick" value="✔" /><input type="button" class="btn btn-primary col-md-2 doubleTick" value="✔✔" /><div class="col-md-4"></div></div><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-danger col-md-2 singleX" value="✗" /><input type="button" class="btn btn-danger col-md-2 doubleX" value="✗✗" /><div class="col-md-4"></div></div></div>';
			}
		}else if(gameMode=='quick-input'){
			var frontCardContent = '<div class="game_question"><div style="font-size:30px;">'+cardHere[curCardX]['c']['question']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['question_notes']+'</div><div style="padding-left:5px;padding-right:5px;"><textarea style="width:100%;" id="cAnswer"></textarea></div></div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerQuestion" value="Answer" /><input type="button" class="btn btn-primary finishGame" value="Finish"></div>';




			var backCardContent = '<div class="game_answer"><div style="font-size:30px;">'+cardHere[curCardX]['c']['answer']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['answer_notes']+'</div></div><div class="game_answer_options" style="text-align: center;"><div class="row"><div class="col-md-2">Answer = </div><input type="button" id="answermong" class="btn col-md-2"/></div><div class="row" style="margin-left:0px;margin-top:20px;"><input type="button" class="btn btn-primary col-md-2" value="Next" id="nextButtonHere"/></div></div>';
			
		}
		


		//var backCardContent = '<div class="game_answer">'+cardHere[curCardX]['c']['answer']+'</div><div class="game_answer_options" style="text-align: center;"><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-primary col-md-2 singleTick" value="✔" /><input type="button" class="btn btn-primary col-md-2 doubleTick" value="✔✔" /><div class="col-md-4"></div></div><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-primary col-md-2 singleX" value="✗" /><input type="button" class="btn btn-primary col-md-2 doubleX" value="✗✗" /><div class="col-md-4"></div></div></div>';
		jQuery('#dashboard-main-content-area').html('<div id="gameContainerParent"><div class="row" id="gameContainer"><div class="front">'+frontCardContent+'</div><div class="back">'+backCardContent+'</div></div></div>');

		jQuery('.game_answer_options').css('display','none');
		//jQuery('.back').css('display','none');
		jQuery('.front').prepend('<div style="float:right;" class="timerContainer">0:00</div><div style="clear:both;"></div>');


		startTimer();


		//jQuery('#gameContainer').html('<div class="front">'+frontCardContent+'</div><div class="back">'+backCardContent+'</div></div>');
		jQuery('#gameContainer').flip({trigger: 'manual'});
		//jQuery('#gameContainer').flip(false);
	}


}

function showCard(cardHere){

	var countHere = cardsQueue.length - 1;
	nextSwitch = 1;
	answerSwitch = 0;

	dynamicAudio.src = '';

	if(typeof dynamicAudio!='undefined'){
		if(dynamicAudio.hasAttribute('loop')==true){
			dynamicAudio.removeAttribute('loop');
		}
	}

	if(curCardX<=countHere){
		if(cardHere[curCardX]['c']['question_sound_slow']!=''){

			if(cardHere[curCardX]['u']['sound']==1){


				dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_slow'];


				dynamicAudio.play();


				jQuery(dynamicAudio).bind('ended', function(){
					if(cardHere[curCardX]['c']['question_sound_fast']!=''){
						dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_fast'];
						dynamicAudio.setAttribute('loop','');

						setTimeout(function(){

							dynamicAudio.play();
							/*var si = setInterval(function(){
								if(answerSwitch==0){
									dynamicAudio.play();
								}else{
									clearInterval(si);
								}
								
							},600);*/
							


						},delayPlay);
			
					}
				});
			}


			


		}else{
			if(cardHere[curCardX]['c']['question_sound_fast']!=''){
				if(cardHere[curCardX]['u']['sound']==1){
					dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_fast'];
					dynamicAudio.setAttribute('loop','');
					setTimeout(function(){
						dynamicAudio.play();
						/*var si = setInterval(function(){
							if(answerSwitch==0){
								dynamicAudio.play();
							}else{
								clearInterval(si);
							}
							
						},600);*/
						


					},delayPlay);
				}
		
			}
		}

		/*if(cardHere[curCardX]['c']['question_sound_fast']!=''){
			dynamicAudio.src = 'files/' + cardHere[curCardX]['c']['question_sound_fast'];
				
			setTimeout(function(){

				while(answerSwitch=0){
					dynamicAudio.play();
				}


			},delayPlay);
		
		}*/




		/*if(cardHere[curCardX]['srs']==0){
			var frontCardContent = '<div class="game_question">'+cardHere[curCardX]['c']['question']+'</div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerAlreadyKnow" value="Already Know" /><input type="button" class="btn btn-primary answerLogicalGuess" value="Logical Guess" /><input type="button" class="btn btn-primary answerDontKnow" value="Don\'t know" /></div>';
		}else{
			var frontCardContent = '<div class="game_question">'+cardHere[curCardX]['c']['question']+'</div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerQuestion" value="Answer" /><input type="button" class="btn btn-primary finishGame" value="Finish"></div>';
		}*/


		if(gameMode=='quick'){
			var frontCardContent = '<div class="game_question"><div style="font-size:30px;">'+cardHere[curCardX]['c']['question']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['question_notes']+'</div></div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerQuestion" value="Answer" /><input type="button" class="btn btn-primary finishGame" value="Finish"></div>';


			if(cardHere[curCardX]['srs']==0){
				var backCardContent = '<div class="game_answer"><div style="font-size:30px;">'+cardHere[curCardX]['c']['answer']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['answer_notes']+'</div></div><div class="game_answer_options" style="text-align: center;"><input type="button" class="btn btn-primary answerAlreadyKnow" value="Already Know" /><input type="button" class="btn btn-primary answerLogicalGuess" value="Logical Guess" /><input type="button" class="btn btn-primary answerDontKnow" value="Don\'t know" /></div>';

			}else{
				var backCardContent = '<div class="game_answer"><div style="font-size:30px;">'+cardHere[curCardX]['c']['answer']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['answer_notes']+'</div></div><div class="game_answer_options" style="text-align: center;"><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-primary col-md-2 singleTick" value="✔" /><input type="button" class="btn btn-primary col-md-2 doubleTick" value="✔✔" /><div class="col-md-4"></div></div><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-danger col-md-2 singleX" value="✗" /><input type="button" class="btn btn-danger col-md-2 doubleX" value="✗✗" /><div class="col-md-4"></div></div></div>';
			}
		}else if(gameMode=='quick-input'){
			var frontCardContent = '<div class="game_question"><div style="font-size:30px;">'+cardHere[curCardX]['c']['question']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['question_notes']+'</div><div style="padding-left:5px;padding-right:5px;"><textarea style="width:100%;" id="cAnswer"></textarea></div></div><div class="game_question_options" style="text-align: center;"><input type="button" class="btn btn-primary answerQuestion" value="Answer" /><input type="button" class="btn btn-primary finishGame" value="Finish"></div>';


			var backCardContent = '<div class="game_answer"><div style="font-size:30px;">'+cardHere[curCardX]['c']['answer']+'</div><div style="padding-left:5px;">'+cardHere[curCardX]['c']['answer_notes']+'</div></div><div class="game_answer_options" style="text-align: center;"><div class="row"><div class="col-md-2">Answer = </div><input type="button" id="answermong" class="btn col-md-2"/></div><div class="row" style="margin-left:0px;margin-top:20px;"><input type="button" class="btn btn-primary col-md-2" value="Next" id="nextButtonHere"/></div></div>';

		}

		
		

		//var backCardContent = '<div class="game_answer">'+cardHere[curCardX]['c']['answer']+'</div><div class="game_answer_options" style="text-align: center;"><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-primary col-md-2 singleTick" value="✔" /><input type="button" class="btn btn-primary col-md-2 doubleTick" value="✔✔" /><div class="col-md-4"></div></div><div class="row"><div class="col-md-4"></div><input type="button" class="btn btn-primary col-md-2 singleX" value="✗" /><input type="button" class="btn btn-primary col-md-2 doubleX" value="✗✗" /><div class="col-md-4"></div></div></div>';


		jQuery('#dashboard-main-content-area').html('<div id="gameContainerParent"><div class="row" id="gameContainer"><div class="front">'+frontCardContent+'</div><div class="back">'+backCardContent+'</div></div></div>');

		jQuery('.game_answer_options').css('display','none');
		//jQuery('.back').css('display','none');
		jQuery('.front').prepend('<div style="float:right;" class="timerContainer">0:00</div><div style="clear:both;"></div>');


		startTimer();




		jQuery('#gameContainer').flip({trigger: 'manual'});
	}


	



}


function startTimer(){
	var minuteCounter = 0;
		var secondAppend = 0;
		globalInterval = setInterval(function(){
			globalTimer++;
			realTime++;
			if(globalTimer==60){
				minuteCounter++;
				globalTimer = 0;
			}
			if(globalTimer>9){
				secondAppend = '';
			}else{
				secondAppend = 0;
			}
			stringTimer = minuteCounter+':'+secondAppend+globalTimer;
			jQuery('.timerContainer').html(minuteCounter+':'+secondAppend+globalTimer);
		},1000);
}
function arrangeCards(){


	if(cardsQueue!=[]){
		var cardsLength = cardsQueue.length - 1;

		



		if(gameMode=='quick' || gameMode=='quick-input'){
			var counter = 0;
			var time = parseInt(new Date().getTime()/1000);
			while(counter<1){
				if(curCardX<=cardsLength){
					if(cardsQueue[curCardX]['due_date']<=time){
						repeating = 0;
						cardsQueue[curCardX]['temp_srs'] = parseInt((new Date().getTime()/1000));
						//cardsQueue[curCardX]['srs'] = parseInt((new Date().getTime()/1000));
						if(firstUse==0){

							showCard(cardsQueue);
							firstUse = 1;
						}else{
							getNextCard(cardsQueue);
						}
						counter=1;
					}else{
						curCardX++;
					}
				}else{
					if(repeating!=1){
						curCardX = 0;
						repeating++;
					}else{
						counter = 1;
						nextSwitch = 1;
						answerSwitch = 1;
						

						alert('Game is over! All cards were played.');
						updateGameCards();
						viewGameMode();
					}
					
					/*counter = 1;
					nextSwitch = 1;
					answerSwitch = 1;
					

					alert('Game is over! All cards were played.');*/




					
					//updateGameCards();
					//viewGameMode();

				}
				
			}
			


			//showCard(cardsQueue);	
		}
		
	}


}

function clearDefaults(){
	repeating = 0;
	clearInterval(globalInterval);
	dynamicAudio.src = '';
	if(typeof dynamicAudio!='undefined'){
		if(dynamicAudio.hasAttribute('loop')==true){
			dynamicAudio.removeAttribute('loop');
		}
	}
	
	realTime = 0;
	//curCardX = 0;
	globalTimer = 0;


}


jQuery('#dashboard-main-content-area').on('click','.superVisedTest',function(e){
	e.preventDefault();
});


jQuery('#dashboard-main-content-area').on('click','#assignDeckClass',function(e){
	

	e.preventDefault();


	var deckids = jQuery('#deck_ids').val();

	var classid = parseInt(jQuery(this).attr('classid'));

	if(deckids==''){
		jQuery('.token-input-list-facebook').addClass('input-border-error');
	}else{


		var deckidsarray = deckids.split(',');
		var dlength = deckidsarray.length;

		for(var x=0;x<dlength;x++){
			deckidsarray[x] = parseInt(deckidsarray[x]);			
		}



		jQuery('#addDeckClassStatus').html('Assigning Deck(s)...');
		jQuery.ajax({

		    url: '/fcard/users/assignDecks',
		    data: {'deckids': deckidsarray,'class_id' : classid},
		    type: 'GET',
		    dataType: 'json',
		    cache:false, 
		    complete : function(data, textStatus, jqXHR){
		       lock = 0;
				var datos = data.responseJSON;
				if(datos['status']=='ok'){
					jQuery('#addDeckClassStatus').html('Successfully assigned.');
				}else{
					jQuery('#addDeckClassStatus').html('Error occured. Please try again.');
				}
			}


		});






	}



});

jQuery('#dashboard-main-content-area').on('click','#assignStudentClass',function(e){
	

	e.preventDefault();


	var studentids = jQuery('#student_ids').val();

	var classid = parseInt(jQuery(this).attr('classid'));

	if(studentids==''){
		jQuery('.token-input-list-facebook').addClass('input-border-error');
	}else{


		var studentidsarray = studentids.split(',');

		var slength = studentidsarray.length;

		for(var x=0;x<slength;x++){
			studentidsarray[x] = parseInt(studentidsarray[x]);			
		}



		jQuery('#addStudentClassStatus').html('Assigning Student(s)...');
		jQuery.ajax({

		    url: '/fcard/users/assignStudents',
		    data: {'studentids': studentidsarray,'class_id' : classid},
		    type: 'GET',
		    dataType: 'json',
		    cache:false, 
		    complete : function(data, textStatus, jqXHR){
		       lock = 0;
				var datos = data.responseJSON;
				if(datos['status']=='ok'){
					jQuery('#addStudentClassStatus').html('Successfully assigned.');
				}else{
					jQuery('#addStudentClassStatus').html('Error occured. Please try again.');
				}
			}


		});






	}



});




jQuery('#dashboard-main-content-area').on('click','#addDeckFilter',function(e){

	lock = 1;
	curCardX = 0;
	clearDefaults();
	var userid = parseInt(jQuery(this).attr('userid'));
	jQuery('#addDeckStatus').html('Saving data...');
	jQuery('#deck_name_error').html('');
    jQuery('#deck_name').removeClass('input-border-error');
        //jQuery('#full_name').attr('style','border:1px solid black;');
	var datas = {'full_name' : jQuery('#deck_name').val(),'user_id' : userid};

		jQuery.ajax({
        url: '/fcard/users/addDeckFilter',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	var datos = data.responseJSON;
        	if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#addDeckStatus').html('');
	        			jQuery('#deck_name_error').html(datos['errors']['full_name']);
	        			//jQuery('#full_name').attr('style','border:1px solid red;');
	        			jQuery('#deck_name').addClass('input-border-error');
	        		}
        		}else
        			jQuery('#addDeckStatus').html('Saved');
        		
       		}else
       			jQuery('#addDeckStatus').html('Error. Please try again.');
        }
		});

});


jQuery('#dashboard-main-content-area').on('click','.assignDeck',function(e){
	
	e.preventDefault();


	var classid =jQuery(this).attr('classid');


	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	lock = 1;
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');


	jQuery.get("/fcard/users/viewAddDeckToClassNow?classid="+classid).done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
				


				//lazy load the token input script
				var t = setInterval(function(){

					if(jQuery('#deck_ids').length>0){
						jQuery("#deck_ids").tokenInput('/fcard/users/searchDeck/',{theme: 'facebook',preventDuplicates : 'true',propertyToSearch: 'name'});
						clearInterval(t);
					}
				},500);


				/*var t = setInterval(function(){
					if(jQuery('#assignStudentClass').length>0){
						clearInterval(t);
						jQuery("#assignStudentClass").attr('classid',classid);
					}
				},100);*/


			}
	);


});

jQuery('#dashboard-main-content-area').on('click','.addDecktoClassNow',function(e){
	
	e.preventDefault();


	var classid =jQuery(this).attr('addclassid');


	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	lock = 1;
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');


	jQuery.get("/fcard/users/viewAddDeckToClassNow?classid="+classid).done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
				


				//lazy load the token input script
				var t = setInterval(function(){

					if(jQuery('#deck_ids').length>0){
						jQuery("#deck_ids").tokenInput('/fcard/users/searchDeck/',{theme: 'facebook',preventDuplicates : 'true',propertyToSearch: 'name'});
						clearInterval(t);
					}
				},500);


				/*var t = setInterval(function(){
					if(jQuery('#assignStudentClass').length>0){
						clearInterval(t);
						jQuery("#assignStudentClass").attr('classid',classid);
					}
				},100);*/


			}
	);


});






jQuery('#dashboard-main-content-area').on('click','.addUsertoClassNow',function(e){
	
	e.preventDefault();


	var classid =jQuery(this).attr('addclassid');


	clearDefaults();
	curCardX = 0;
	e.preventDefault();
	lock = 1;
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');


	jQuery.get("/fcard/users/viewAddUserToClassNow?classid="+classid).done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
				


				//lazy load the token input script
				var t = setInterval(function(){

					if(jQuery('#student_ids').length>0){
						jQuery("#student_ids").tokenInput('/fcard/users/searchStudent/',{theme: 'facebook',preventDuplicates : 'true',propertyToSearch: 'name'});
						clearInterval(t);
					}
				},500);


				/*var t = setInterval(function(){
					if(jQuery('#assignStudentClass').length>0){
						clearInterval(t);
						jQuery("#assignStudentClass").attr('classid',classid);
					}
				},100);*/


			}
	);


});

jQuery('#dashboard-main-content-area').on('click','#reviewLogPagination a',function(e){

	e.preventDefault();
	if(jQuery(this).parent().hasClass('disabled')){
		
	}else{
		if(paglock==0){
			paglock = 1;
			var href = jQuery(this).attr('href');
			jQuery.get(href).done(
				function( data ){
					jQuery('#dashboard-main-content-area').html(data);
					paglock = 0;
				}
			);	
		}
	}

	/*if(paglock==0){
		paglock = 1;
		e.preventDefault();
		var href = jQuery(this).attr('href');
		jQuery.get(href).done(
			function( data ){
				jQuery('#dashboard-main-content-area').html(data);
				paglock = 0;
			}
		);	
	}*/

	




});


function viewGameSettings(){

	clearDefaults();
	curCardX = 0;
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/viewGameSettings").done(
		function( data ){
			lock = 0;
			jQuery('#dashboard-main-content-area').html(data);
		}
	);


}

function viewGameMode(){
	clearDefaults();
	curCardX = 0;
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/viewGameMode").done(
		function( data ){
			lock = 0;
			jQuery('#dashboard-main-content-area').html(data);
		}
	);
}




function startQuickNow(deckids){
	lock = 1;
	curCardX = 0;
	clearDefaults();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');


	jQuery.ajax({
	    url: '/fcard/users/startQuickGame',
	    data: {'deckids': deckids},
	    type: 'GET',
	    dataType: 'json',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	       lock = 0;
			var datos = data.responseJSON;

			if(datos['status']=='ok'){
				cardsQueue = datos['cards'];
				soundPath = datos['path'];
				//gameMode = 'quick';
				arrangeCards();

			}else{
				alert('Error. Possibly there are no cards added on this deck yet.');
				viewGameMode();
			}
	    }
	});

}




function startQuickStartAll(){
	lock = 1;
	curCardX = 0;
	clearDefaults();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/startQuickGame").done(
			function( data ){
				lock = 0;
				//var datos = jQuery.parseJSON(data);
				jQuery('#dashboard-main-content-area').html(data);
			}
	);

}



function startQuickStart(){
	lock = 1;
	curCardX = 0;
	clearDefaults();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/startQuickStart").done(
			function( data ){
				lock = 0;
				//var datos = jQuery.parseJSON(data);
				jQuery('#dashboard-main-content-area').html(data);
			}
	);




}




function startQuickGame(){
	lock = 1;
	curCardX = 0;
	clearDefaults();
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
	jQuery.get("/fcard/users/startQuickGame").done(
			function( data ){
				lock = 0;
				var datos = jQuery.parseJSON(data);

				//var cards = jQuery.parseJSON(data);

				if(datos['status']=='ok'){
					cardsQueue = datos['cards'];
					//gameMode = 'quick';
					arrangeCards();

				}else{

					if(datos['renew']==1){
						jQuery('#dashboard-main-content-area').html(datos['content']);
					}else{
						alert('Error. Possibly there are no cards or decks created yet.');
						viewGameMode();
					}
					
					//alert('Error. Possibly there are no cards or decks created yet.');
					//viewGameMode();
				}
				
				//jQuery('#dashboard-main-content-area').html('');
				//console.log(datos);
				//jQuery('#dashboard-main-content-area').html(data);
			}
	);



}

function updateGameCardInput(classHere){
	//jQuery(classHere).addClass('disabled');
	//lock = 1;
	jQuery.ajax({
	    url: '/fcard/users/updateGameCardIndividual',
	    data: {'datas': cardsQueue[curCardX]},
	    type: 'GET',
	    dataType: 'json',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	        //lock = 0;
	        var datos = data.responseJSON;
	        if(datos['status']=='ok'){
	        	//jQuery(classHere).removeClass('disabled');
	        	//curCardX++;
	        	//arrangeCards();
	        }
	    }
	});

	jQuery.ajax({
				    url: '/fcard/users/updateLogs',
				    data: {'datas': cardsQueue[curCardX],'gameMode' : gameMode},
				    type: 'GET',
				    dataType: 'json',
				    cache:false, 
				    complete : function(data, textStatus, jqXHR){
				    	var datos = data.responseJSON;
				    }

				});
	
	var cardsLength = cardsQueue.length - 1;
	curCardX++;


	if(curCardX>cardsLength){
		curCardX = 0;
	}
}

function updateGameCard(classHere){
	//jQuery(classHere).addClass('disabled');
	//lock = 1;
	jQuery.ajax({
	    url: '/fcard/users/updateGameCardIndividual',
	    data: {'datas': cardsQueue[curCardX]},
	    type: 'GET',
	    dataType: 'json',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	        //lock = 0;
	        var datos = data.responseJSON;
	        if(datos['status']=='ok'){
	        	//jQuery(classHere).removeClass('disabled');
	        	//curCardX++;
	        	//arrangeCards();
	        }
	    }
	});

	jQuery.ajax({
				    url: '/fcard/users/updateLogs',
				    data: {'datas': cardsQueue[curCardX],'gameMode' : gameMode},
				    type: 'GET',
				    dataType: 'json',
				    cache:false, 
				    complete : function(data, textStatus, jqXHR){
				    	var datos = data.responseJSON;
				    }

				});
	
	var cardsLength = cardsQueue.length - 1;
	curCardX++;


	if(curCardX>cardsLength){
		curCardX = 0;
	}
	arrangeCards();

	console.log(curCardX);

}

function updateGameCards(){
	lock = 1;
	//updates cards now
	jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');

	viewGameMode();
	/*jQuery.ajax({
	    url: '/fcard/users/updateGameCard',
	    data: {'datas': cardsQueue},
	    type: 'POST',
	    dataType: 'json',
	    cache:false, 
	    complete : function(data, textStatus, jqXHR){
	        lock = 0;
	        var datos = data.responseJSON;
	        if(datos['status']=='ok'){
	        	viewGameMode();
	        }else{
	        	alert('Error occured. Please try again.');
	        }
	    }
	});*/
}



jQuery(document).ready(function(){
	
	dynamicAudio = document.createElement('audio');
	dynamicAudio.id = 'dynamicAudio';
	dynamicAudio.controls = true;
	dynamicAudio.setAttribute('style','display:none;');
	//dynamicAudio.setAttribute('style','display:none;');
	var bd = document.getElementsByTagName('body')[0];
	bd.appendChild(dynamicAudio);



	jQuery('#dashboard-main-content-area').on('click','#checkTest',function(e){

		var deckids = Array();


		jQuery('li.deckList').each(function(key,value){
			if(jQuery(value).find('input[type="checkbox"]').prop('checked')==true){
				var splitter = jQuery(value).find('input[type="checkbox"]').attr('id');
				var explode = splitter.split('-');
				deckids.push(parseInt(explode[1]));
			}

		});
		
		if(deckids.length==0){
			alert('Select first a deck');
		}else{
			startQuickNow(deckids);
		}



	});


	jQuery('#dashboard-main-content-area').on('click','.finishGame',function(e){
		var r = confirm('Are you sure you want to finish the game?');
		if(r==true){
			updateGameCards();
			
		}

	});

	jQuery('#dashboard-main-content-area').on('click','.doubleX',function(e){
		var compute = parseInt(parseInt(cardsQueue[curCardX]['srt']) * 1.5);
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];


		cardsQueue[curCardX]['before_srt'] = cardsQueue[curCardX]['srt'];
		if(cardsQueue[curCardX]['aet']>=compute){
			
		}else{
			cardsQueue[curCardX]['srt'] = parseFloat(parseInt(cardsQueue[curCardX]['aet']) * parseFloat(cardsQueue[curCardX]['u']['dx']));
		}
		//cardsQueue[curCardX]['srt'] = parseFloat(parseFloat(cardsQueue[curCardX]['srt']) * parseFloat(cardsQueue[curCardX]['u']['dx']));
		cardsQueue[curCardX]['due_date'] = parseInt(parseInt(cardsQueue[curCardX]['srs']) + parseInt(cardsQueue[curCardX]['srt']));
		cardsQueue[curCardX]['before_history'] = cardsQueue[curCardX]['history'];
		cardsQueue[curCardX]['history'] = cardsQueue[curCardX]['history'] + '%'; 
		cardsQueue[curCardX]['dxcounter'] =parseInt(parseInt(cardsQueue[curCardX]['dxcounter']) + parseInt(1));
		cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
		cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) - 10;

		if(cardsQueue[curCardX]['rank']<0){
			cardsQueue[curCardX]['rank'] = 0;
		}

		cardsQueue[curCardX]['mark_as'] = 'dx';
	

		//curCardX = 0;
		//curCardX++;
		updateGameCard('.doubleX');
		//console.log(cardsQueue[curCardX]['srt']+' '+cardsQueue[curCardX]['due_date']);
		//arrangeCards();

	});
	
	



	jQuery('#fcard-menu').on('click','.quickStandardMistakeLog',function(e){


		lock =1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;

		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/quickStandardMistakeLog").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);




	});




	jQuery('#fcard-menu').on('click','.viewGameSettings',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;


		viewGameSettings();



	});

	jQuery('#fcard-menu').on('click','.manageTeachers',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/manageTeachers").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});





	jQuery('#fcard-menu').on('click','.manageClasses',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/manageClasses").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});


	
	jQuery('#fcard-menu').on('click','.manageStudents',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/manageStudents").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});


	jQuery('#dashboard-main-content-area').on('click','.singleX',function(e){
		var compute = cardsQueue[curCardX]['srt'] * 1.5;


		cardsQueue[curCardX]['before_srt'] = cardsQueue[curCardX]['srt'];


		if(cardsQueue[curCardX]['aet']>=compute){
			
		}else{
			cardsQueue[curCardX]['srt'] = parseFloat(parseInt(cardsQueue[curCardX]['aet']) * parseFloat(cardsQueue[curCardX]['u']['sx']));
		}
		
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];
		cardsQueue[curCardX]['mark_as'] = 'sx';
		//cardsQueue[curCardX]['srt'] = parseFloat(parseFloat(cardsQueue[curCardX]['srt']) * parseFloat(cardsQueue[curCardX]['u']['sx']));
		cardsQueue[curCardX]['due_date'] = parseInt(parseInt(cardsQueue[curCardX]['srs']) + parseInt(cardsQueue[curCardX]['srt']));
		cardsQueue[curCardX]['before_history'] = cardsQueue[curCardX]['history'];
		cardsQueue[curCardX]['history'] = cardsQueue[curCardX]['history'] + 'x'; 
		cardsQueue[curCardX]['sxcounter'] =parseInt(parseInt(cardsQueue[curCardX]['sxcounter']) + parseInt(1));
		cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
		cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) - 5;


		if(cardsQueue[curCardX]['rank']<0){
			cardsQueue[curCardX]['rank'] = 0;
		}
		//curCardX = 0;
		//curCardX++;
		updateGameCard('.singleX');
		//console.log(cardsQueue[curCardX]['srt']+' '+cardsQueue[curCardX]['due_date']);
		//arrangeCards();

	});



	jQuery('#dashboard-main-content-area').on('click','.doubleTick',function(e){
		cardsQueue[curCardX]['mark_as'] = 'dt';
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];
		cardsQueue[curCardX]['before_srt'] = cardsQueue[curCardX]['srt'];
		cardsQueue[curCardX]['srt'] = parseInt(parseInt(cardsQueue[curCardX]['aet']) * parseInt(cardsQueue[curCardX]['u']['dt']));
		//cardsQueue[curCardX]['srt'] = parseFloat(parseFloat(cardsQueue[curCardX]['srt']) + parseFloat(cardsQueue[curCardX]['u']['dt']));
		cardsQueue[curCardX]['due_date'] = parseInt(parseInt(cardsQueue[curCardX]['srs']) + parseInt(cardsQueue[curCardX]['srt']));
		cardsQueue[curCardX]['before_history'] = cardsQueue[curCardX]['history'];
		cardsQueue[curCardX]['history'] = cardsQueue[curCardX]['history'] + ':)';
		cardsQueue[curCardX]['dtcounter'] =parseInt(parseInt(cardsQueue[curCardX]['dtcounter']) + parseInt(1));
		cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
		cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) + 2;
		//curCardX = 0;
		//curCardX++;
		updateGameCard('.doubleTick');
		//console.log(cardsQueue[curCardX]['srt']+' '+cardsQueue[curCardX]['due_date']);
		//arrangeCards();

	});


	jQuery('#dashboard-main-content-area').on('click','.singleTick',function(e){
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];
		cardsQueue[curCardX]['before_srt'] = cardsQueue[curCardX]['srt'];
		cardsQueue[curCardX]['srt'] = parseInt(parseInt(cardsQueue[curCardX]['aet']) * parseInt(cardsQueue[curCardX]['u']['st']));
		//cardsQueue[curCardX]['srt'] = parseFloat(parseFloat(cardsQueue[curCardX]['srt']) + parseFloat(cardsQueue[curCardX]['u']['st']));
		cardsQueue[curCardX]['due_date'] = parseInt(parseInt(cardsQueue[curCardX]['srs']) + parseInt(cardsQueue[curCardX]['srt']));
		cardsQueue[curCardX]['stcounter'] =parseInt(parseInt(cardsQueue[curCardX]['stcounter']) + parseInt(1));
		cardsQueue[curCardX]['before_history'] = cardsQueue[curCardX]['history'];
		cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
		cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) + 1;
		cardsQueue[curCardX]['history'] = cardsQueue[curCardX]['history'] + '0';
		cardsQueue[curCardX]['mark_as'] = 'st';
		//curCardX = 0;
		//curCardX++;
		updateGameCard('.singleTick');
		//console.log(cardsQueue[curCardX]['srt']+' '+cardsQueue[curCardX]['due_date']);
		//arrangeCards();

	});



	jQuery('#dashboard-main-content-area').on('click','.answerDontKnow',function(e){
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];
		cardsQueue[curCardX]['first_time_marked_dont_know'] = 1;
		cardsQueue[curCardX]['srs'] = cardsQueue[curCardX]['temp_srs'];
		cardsQueue[curCardX]['before_history'] = '';
		cardsQueue[curCardX]['temp_srs'] = 0;
		cardsQueue[curCardX]['before_rank'] = 0;
		cardsQueue[curCardX]['rank'] = 0;
		//curCardX++;
		updateGameCard('.answerDontKnow');
		//clearDefaults();

	});


	jQuery('#dashboard-main-content-area').on('click','.answerLogicalGuess',function(e){
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];
		cardsQueue[curCardX]['first_time_marked_logical_guess'] = 1;
		cardsQueue[curCardX]['srs'] = cardsQueue[curCardX]['temp_srs'];
		cardsQueue[curCardX]['before_history'] = '';
		cardsQueue[curCardX]['before_rank'] = 0;
		cardsQueue[curCardX]['rank'] = 0;
		cardsQueue[curCardX]['temp_srs'] = 0;
		//curCardX++;
		updateGameCard('.answerLogicalGuess');
		//clearDefaults();

	});
	

	jQuery('#dashboard-main-content-area').on('click','.answerAlreadyKnow',function(e){
		cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
		cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
		cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
		cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];
		cardsQueue[curCardX]['first_time_marked_already_know'] = 1;
		cardsQueue[curCardX]['srs'] = cardsQueue[curCardX]['temp_srs'];
		cardsQueue[curCardX]['before_history'] = '';
		cardsQueue[curCardX]['before_rank'] = 0;
		cardsQueue[curCardX]['rank'] = 0;
		cardsQueue[curCardX]['temp_srs'] = 0;
		//curCardX++;
		updateGameCard('.answerAlreadyKnow');
		//clearDefaults();

	});


	jQuery('#dashboard-main-content-area').on('click','.answerQuestion',function(e){

		dynamicAudio.src = '';

		if(typeof dynamicAudio!='undefined'){
			if(dynamicAudio.hasAttribute('loop')==true){
				dynamicAudio.removeAttribute('loop');
			}
		}




		if(gameMode=='quick-input'){
			var correctAnswer =jQuery.trim(cardsQueue[curCardX]['c']['answer']);

			correctAnswer = correctAnswer.toLowerCase();


			var answerUser = jQuery.trim(jQuery('#cAnswer').val());

			answerUser = answerUser.toLowerCase();
		}


		






		jQuery('#gameContainer').flip(true);
		jQuery("#gameContainer").on('flip:done',function(){


			answerSwitch = 1;
			nextSwitch = 0;
			

			if(cardsQueue[curCardX]['c']['answer_sound_slow']!=''){
				if(cardsQueue[curCardX]['u']['sound']==1){


				dynamicAudio.src = 'files/' + cardsQueue[curCardX]['c']['answer_sound_slow'];;

				dynamicAudio.play();
				console.log(dynamicAudio.src);


				jQuery(dynamicAudio).bind('ended', function(){
					if(cardsQueue[curCardX]['c']['answer_sound_fast']!=''){
						dynamicAudio.src = 'files/' + cardsQueue[curCardX]['c']['answer_sound_fast'];
						dynamicAudio.setAttribute('loop','');
						setTimeout(function(){
							dynamicAudio.play();
							/*var si = setInterval(function(){
								if(nextSwitch==0){
									dynamicAudio.play();
								}else{
									clearInterval(si);
								}
								
							},600);*/
							


						},delayPlay);
		
					}
				});

				}		

				





			}else{
				if(cardsQueue[curCardX]['c']['answer_sound_fast']!=''){
					if(cardsQueue[curCardX]['u']['sound']==1){
						dynamicAudio.src = 'files/' + cardsQueue[curCardX]['c']['answer_sound_fast'];
						dynamicAudio.setAttribute('loop','');
							setTimeout(function(){
								dynamicAudio.play();
									/*var si = setInterval(function(){
										if(answerSwitch==0){
											dynamicAudio.play();
										}else{
											clearInterval(si);
										}
										
									},600);*/
								},delayPlay);
					}

					
		
				}
			}




			if(gameMode=='quick-input'){

				cardsQueue[curCardX]['user_input'] = answerUser;
				
				if(answerUser==correctAnswer){




					//jQuery('#answermong').addClass('singleTick');
					jQuery('#answermong').val('✔');
					jQuery('#answermong').addClass('btn-primary');


					cardsQueue[curCardX]['mark_as'] = 'st';

					cardsQueue[curCardX]['sx'] = cardsQueue[curCardX]['u']['sx'];
					cardsQueue[curCardX]['dx'] = cardsQueue[curCardX]['u']['dx'];
					cardsQueue[curCardX]['st'] = cardsQueue[curCardX]['u']['st'];
					cardsQueue[curCardX]['dt'] = cardsQueue[curCardX]['u']['dt'];


					if(cardsQueue[curCardX]['srs']==0){
						cardsQueue[curCardX]['first_time_marked_already_know'] = 1;
						cardsQueue[curCardX]['srs'] = cardsQueue[curCardX]['temp_srs'];
						cardsQueue[curCardX]['before_history'] = '';
						cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
						cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) + 1;
						cardsQueue[curCardX]['temp_srs'] = 0;
						//curCardX++;


						updateGameCardInput('.answerAlreadyKnow');

						//jQuery('#nextButtonHere').attr('onclick','updateGameCard(\'.answerAlreadyKnow\');');

						jQuery('#nextButtonHere').attr('onclick','arrangeCards();');


					}else{
						cardsQueue[curCardX]['before_srt'] = cardsQueue[curCardX]['srt'];
						cardsQueue[curCardX]['srt'] = parseInt(parseInt(cardsQueue[curCardX]['aet']) * parseInt(cardsQueue[curCardX]['u']['st']));
						//cardsQueue[curCardX]['srt'] = parseFloat(parseFloat(cardsQueue[curCardX]['srt']) + parseFloat(cardsQueue[curCardX]['u']['st']));
						cardsQueue[curCardX]['due_date'] = parseInt(parseInt(cardsQueue[curCardX]['srs']) + parseInt(cardsQueue[curCardX]['srt']));
						cardsQueue[curCardX]['stcounter'] =parseInt(parseInt(cardsQueue[curCardX]['stcounter']) + parseInt(1));
						cardsQueue[curCardX]['before_history'] = cardsQueue[curCardX]['history'];
						cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
						cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) + 1;
						cardsQueue[curCardX]['history'] = cardsQueue[curCardX]['history'] + '0';
						//curCardX = 0;
						//curCardX++;


						updateGameCardInput('.singleTick');
						//jQuery('#nextButtonHere').attr('onclick','updateGameCard(\'.singleTick\');');
						jQuery('#nextButtonHere').attr('onclick','arrangeCards();');
					}



					

					
					//updateGameCard('.singleTick');



				}else{
					//jQuery('#answermong').addClass('singleX');
					jQuery('#answermong').val('✗');
					jQuery('#answermong').addClass('btn-danger');

					cardsQueue[curCardX]['mark_as'] = 'sx';
					if(cardsQueue[curCardX]['srs']==0){

						cardsQueue[curCardX]['first_time_marked_dont_know'] = 1;
						cardsQueue[curCardX]['srs'] = cardsQueue[curCardX]['temp_srs'];
						cardsQueue[curCardX]['before_history'] = '';
						cardsQueue[curCardX]['temp_srs'] = 0;
						cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
						cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) - 5;

						if(cardsQueue[curCardX]['rank']<0){
							cardsQueue[curCardX]['rank'] = 0;
						}
						//curCardX++;


						updateGameCardInput('.answerDontKnow');
						jQuery('#nextButtonHere').attr('onclick','arrangeCards();');


						//jQuery('#nextButtonHere').attr('onclick','updateGameCard(\'.answerDontKnow\');');

					}else{

						var compute = cardsQueue[curCardX]['srt'] * 1.5;


						cardsQueue[curCardX]['before_srt'] = cardsQueue[curCardX]['srt'];


						if(cardsQueue[curCardX]['aet']>=compute){
							
						}else{
							cardsQueue[curCardX]['srt'] = parseFloat(parseInt(cardsQueue[curCardX]['aet']) * parseFloat(cardsQueue[curCardX]['u']['sx']));
						}
						
						//cardsQueue[curCardX]['srt'] = parseFloat(parseFloat(cardsQueue[curCardX]['srt']) * parseFloat(cardsQueue[curCardX]['u']['sx']));
						cardsQueue[curCardX]['due_date'] = parseInt(parseInt(cardsQueue[curCardX]['srs']) + parseInt(cardsQueue[curCardX]['srt']));
						cardsQueue[curCardX]['before_history'] = cardsQueue[curCardX]['history'];
						cardsQueue[curCardX]['history'] = cardsQueue[curCardX]['history'] + 'x'; 
						cardsQueue[curCardX]['sxcounter'] =parseInt(parseInt(cardsQueue[curCardX]['sxcounter']) + parseInt(1));
						cardsQueue[curCardX]['before_rank'] = parseInt(cardsQueue[curCardX]['rank']);
						cardsQueue[curCardX]['rank'] = parseInt(cardsQueue[curCardX]['rank']) - 5;


						if(cardsQueue[curCardX]['rank']<0){
							cardsQueue[curCardX]['rank'] = 0;
						}
						//curCardX = 0;
						//curCardX++;
						updateGameCardInput('.singleX');
						jQuery('#nextButtonHere').attr('onclick','arrangeCards();');
						//jQuery('#nextButtonHere').attr('onclick','updateGameCard(\'.singleX\');');
					}



					//start
					
					//updateGameCard('.singleX');


					//end
				}
			}

			


			
			/*if(cardsQueue[curCardX]['c']['answer_sound_fast']!=''){
				dynamicAudio.src = 'files/' + cardsQueue[curCardX]['c']['answer_sound_fast'];
				
				setTimeout(function(){
					while(nextSwitch=0){
						dynamicAudio.play();
					}
				},delayPlay);
				
			}*/




			jQuery('.game_answer_options').css('display','block');




		});
		
		//jQuery('.back').css('display','block');
		if(cardsQueue[curCardX]['srs']>0){
			cardsQueue[curCardX]['aet'] = parseInt(parseInt(new Date().getTime()/1000) - parseInt(cardsQueue[curCardX]['srs']));
			cardsQueue[curCardX]['srs'] = cardsQueue[curCardX]['temp_srs'];
			cardsQueue[curCardX]['temp_srs'] = 0;
		}
		
		//cardsQueue[curCardX]['srs'] = parseInt((new Date().getTime()/1000));

		jQuery('.back').prepend('<div style="float:right;" class="timerContainer">'+stringTimer+'</div><div style="clear:both;"></div>');
		clearDefaults();
		
	});


	jQuery('#dashboard-main-content-area').on('click','.edit-card-now',function(e){
		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var thisa = this;

		if(jQuery(this).html()=='Edit'){
			jQuery(this).html('Loading...');
			var editid = jQuery(this).attr('editid');
			var datas = {'card_id' : editid};
			jQuery.ajax({
	        url: '/fcard/users/editCard',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	jQuery(thisa).html('Save');
	        	lock = 0;
	        	var datos = data.responseJSON;
	        	if(datos['status']=='not ok')
	        		alert('Error. Please try again.');
	        	else{
	        		jQuery('#card-'+editid).find('.firsttd').html('<strong>Q</strong> - <textarea id="card_question" style="height:160px;width:100%;">'+datos['c']['question']+'</textarea><br/><strong>N</strong> - <textarea id="card_question_notes" style="height:160px;width:100%;">'+datos['c']['question_notes']+'</textarea>');
	        		//jQuery('#card-'+editid).find('td:nth-child(3)').html('<textarea id="card_question_notes" style="height:160px;">'+datos['c']['question_notes']+'</textarea>');
	        		jQuery('#card-'+editid).find('.secondtd').html('<strong>A</strong> - <textarea id="card_answer" style="height:160px;width:100%;">'+datos['c']['answer']+'</textarea><br/><strong>N</strong> - <textarea id="card_answer_notes" style="height:160px;width:100%;">'+datos['c']['answer_notes']+'</textarea>');

	        		//jQuery('#card-'+editid).find('td:nth-child(5)').html('<textarea id="card_answer_notes" style="height:160px;">'+datos['c']['answer_notes']+'</textarea>');
	        	}
	        	
	        }
			});
		}else{
			//console.log(jQuery('#card-'+editid).find('td:nth-child(2) textarea:first').val());
			jQuery(this).html('Saving...');
			var editid = jQuery(this).attr('editid');
			var datas = {'card_id' : editid,'question' : jQuery('#card-'+editid).find('.firsttd').find('#card_question').val(),'question_notes' : jQuery('#card-'+editid).find('.firsttd').find('#card_question_notes').val(),'answer' : jQuery('#card-'+editid).find('.secondtd').find('#card_answer').val(),'answer_notes' : jQuery('#card-'+editid).find('.secondtd').find('#card_answer_notes').val()};

		

			jQuery.ajax({
	        url: '/fcard/users/saveEditCard',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	
	        	lock = 0;
	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		if(datos['errors']){
	        			jQuery(thisa).html('Save');

	        			if(datos['errors']['question'])
	       					jQuery('#card-'+editid).find('.firsttd').find('#card_question').addClass('input-border-error');
	       				if(datos['errors']['question_notes'])
	       					jQuery('#card-'+editid).find('.firsttd').find('#card_question_notes').addClass('input-border-error');
        				if(datos['errors']['answer'])
	       					jQuery('#card-'+editid).find('.secondtd').find('#card_answer').addClass('input-border-error');
	       				if(datos['errors']['answer_notes'])
	       					jQuery('#card-'+editid).find('.secondtd').find('#card_answer_notes').addClass('input-border-error');
        			}else{

        				jQuery(thisa).html('Edit');
        				jQuery('#card-'+editid).find('td.firsttd').html('<strong>Q</strong> - '+ jQuery('#card-'+editid).find('.firsttd').find('#card_question').val()+'<br/><strong>N</strong> - ' + jQuery('#card-'+editid).find('.firsttd').find('#card_question_notes').val());
        				jQuery('#card-'+editid).find('.secondtd').html('<strong>A</strong> - '+ jQuery('#card-'+editid).find('.secondtd').find('#card_answer').val()+'<br/><strong>N</strong> - ' + jQuery('#card-'+editid).find('.secondtd').find('#card_answer_notes').val());


        			}
	        		
	        	}else{
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
			});



		}
		
		


		
	});
	


	jQuery('#dashboard-main-content-area').on('click','.add-multiple-card-now',function(e){
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		lock = 1;
		var deckid = jQuery(this).attr('adddeckid');
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewAddMultipleCards").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
				
				var t = setInterval(function(){
					if(jQuery('#addCardMultiple').length>0){
						clearInterval(t);
						jQuery("#addCardMultiple").attr('deckid',deckid);
						t = setInterval(function(){
							if(jQuery('.viewDeckInfo').length>0){
								clearInterval(t);
								jQuery('.viewDeckInfo').attr('deckid',deckid);
							}
						},100);
					}
				},100);


			}
		);

	});
	


	jQuery('#dashboard-main-content-area').on('click','#playQuickInputAll',function(e){
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		gameMode = 'quick-input';
		//startQuickGame();
		startQuickGame();
	});


	jQuery('#dashboard-main-content-area').on('click','#playQuickInput',function(e){
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		gameMode = 'quick-input';
		//startQuickGame();
		startQuickStart();
	});

	jQuery('#dashboard-main-content-area').on('click','#playQuick',function(e){
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		gameMode = 'quick';
		//startQuickGame();
		startQuickStart();
	});

	


	jQuery('#dashboard-main-content-area').on('click','playQuickReverseAll',function(e){
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		/*gameMode = 'reverse';
		//startQuickGame();
		startQuickGame();*/

		alert('atay');

	});




	jQuery('#dashboard-main-content-area').on('click','#playQuickAll',function(e){
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		gameMode = 'quick';
		//startQuickGame();
		startQuickGame();
	});

	jQuery('#dashboard-main-content-area').on('click','.deleteDeck',function(e){
		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to delete this deck?');

		if(r==true){
			var thisa = this;
			var deckid = jQuery(this).attr('deckid');
			var datas = {'deck_id' : deckid};
			jQuery(this).html('Deleting...');
			jQuery.ajax({
	        url: '/fcard/users/deleteDeck',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;

	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#deck-'+deckid).remove();
	        	}else{
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}
		


	});


	jQuery('#dashboard-main-content-area').on('click','.delete-card-now',function(e){
		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to delete this card?');

		if(r==true){
			var thisa = this;
			var cardid = jQuery(this).attr('deleteid');
			var datas = {'card_id' : cardid};
			jQuery(this).html('Deleting...');
			jQuery.ajax({
	        url: '/fcard/users/deleteCard',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;

	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#card-'+cardid).remove();
	        	}else{
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}
		


	});

	jQuery('#dashboard-main-content-area').on('click','.add-card-now',function(e){
		lock = 1;
		curCardX = 0;
		clearDefaults();
		e.preventDefault();
		var deckid = jQuery(this).attr('adddeckid');
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewAddCard").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
				
				var t = setInterval(function(){
					if(jQuery('#addCard').length>0){
						clearInterval(t);
						jQuery("#addCard").attr('deckid',deckid);
					}
				},100);


			}
		);
		

	});


	jQuery('#dashboard-main-content-area').on('click','#addStudent',function(){
		lock = 1;
		curCardX = 0;
		clearDefaults();



		jQuery('#addStudentStatus').html('Saving data...');
		jQuery('#student_name_error').html('');
		jQuery('#student_email_address_error').html('');
		jQuery('#student_username_error').html('');
		jQuery('#student_password_error').html('');
		jQuery('#student_repassword_error').html('');

		jQuery('#student_email_address').removeClass('input-border-error');
		jQuery('#student_username').removeClass('input-border-error');
        jQuery('#student_name').removeClass('input-border-error');
        jQuery('#student_password').removeClass('input-border-error');
        jQuery('#student_repassword').removeClass('input-border-error');





        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'full_name' : jQuery('#student_name').val(),'email_address' : jQuery('#student_email_address').val(),'username' : jQuery('#student_username').val(),'password' : jQuery('#student_password').val(),'repassword' : jQuery('#student_repassword').val()};

		jQuery.ajax({
        url: '/fcard/users/addStudent',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	var datos = data.responseJSON;
        	if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#addstudentStatus').html('');
	        			jQuery('#student_name_error').html(datos['errors']['full_name']);
	        			jQuery('#student_name').addClass('input-border-error');
	        		}


	        		if(datos['errors']['email_address']){
	        			jQuery('#addstudentStatus').html('');
	        			jQuery('#student_email_address_error').html(datos['errors']['email_address']);
	        			jQuery('#student_email_address').addClass('input-border-error');	
	        		}

	        		if(datos['errors']['username']){
	        			jQuery('#addStudentStatus').html('');
	        			jQuery('#student_username_error').html(datos['errors']['username']);
	        			jQuery('#student_username').addClass('input-border-error');	
	        		}

	        		if(datos['errors']['password']){
	        			jQuery('#addStudentStatus').html('');
	        			jQuery('#student_password_error').html(datos['errors']['password']);
	        			jQuery('#student_password').addClass('input-border-error');	
	        		}


	        		if(datos['errors']['repassword']){
	        			jQuery('#addStudentStatus').html('');
	        			jQuery('#student_repassword_error').html(datos['errors']['repassword']);
	        			jQuery('#student_repassword').addClass('input-border-error');	
	        		}


        		}else
        			jQuery('#addStudentStatus').html('Saved');
        		
       		}else
       			jQuery('#addStudentStatus').html('Error. Please try again.');
        }
		});
	});


	


	

	jQuery('#dashboard-main-content-area').on('click','#addTeacher',function(){
		lock = 1;
		curCardX = 0;
		clearDefaults();



		jQuery('#addTeacherStatus').html('Saving data...');
		jQuery('#teacher_name_error').html('');
		jQuery('#teacher_email_address_error').html('');
		jQuery('#teacher_username_error').html('');
		jQuery('#teacher_password_error').html('');
		jQuery('#teacher_repassword_error').html('');

		jQuery('#teacher_email_address').removeClass('input-border-error');
		jQuery('#teacher_username').removeClass('input-border-error');
        jQuery('#teacher_name').removeClass('input-border-error');
        jQuery('#teacher_password').removeClass('input-border-error');
        jQuery('#teacher_repassword').removeClass('input-border-error');





        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'full_name' : jQuery('#teacher_name').val(),'email_address' : jQuery('#teacher_email_address').val(),'username' : jQuery('#teacher_username').val(),'password' : jQuery('#teacher_password').val(),'repassword' : jQuery('#teacher_repassword').val()};

		jQuery.ajax({
        url: '/fcard/users/addTeacher',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	var datos = data.responseJSON;
        	if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#addTeacherStatus').html('');
	        			jQuery('#teacher_name_error').html(datos['errors']['full_name']);
	        			jQuery('#teacher_name').addClass('input-border-error');
	        		}


	        		if(datos['errors']['email_address']){
	        			jQuery('#addTeacherStatus').html('');
	        			jQuery('#teacher_email_address_error').html(datos['errors']['email_address']);
	        			jQuery('#teacher_email_address').addClass('input-border-error');	
	        		}

	        		if(datos['errors']['username']){
	        			jQuery('#addTeacherStatus').html('');
	        			jQuery('#teacher_username_error').html(datos['errors']['username']);
	        			jQuery('#teacher_username').addClass('input-border-error');	
	        		}

	        		if(datos['errors']['password']){
	        			jQuery('#addTeacherStatus').html('');
	        			jQuery('#teacher_password_error').html(datos['errors']['password']);
	        			jQuery('#teacher_password').addClass('input-border-error');	
	        		}


	        		if(datos['errors']['repassword']){
	        			jQuery('#addTeacherStatus').html('');
	        			jQuery('#teacher_repassword_error').html(datos['errors']['repassword']);
	        			jQuery('#teacher_repassword').addClass('input-border-error');	
	        		}


        		}else
        			jQuery('#addTeacherStatus').html('Saved');
        		
       		}else
       			jQuery('#addTeacherStatus').html('Error. Please try again.');
        }
		});
	});
	

	jQuery('#dashboard-main-content-area').on('click','#addClassFilter',function(){
		lock = 1;
		curCardX = 0;
		clearDefaults();
		jQuery('#addClassStatus').html('Saving data...');
		jQuery('#class_name_error').html('');
        jQuery('#class_name').removeClass('input-border-error');
        //jQuery('#full_name').attr('style','border:1px solid black;');

        var userid = parseInt(jQuery(this).attr('userid'));


		var datas = {'full_name' : jQuery('#class_name').val(),'user_id' : userid};

		jQuery.ajax({
        url: '/fcard/users/addClassFilter',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	var datos = data.responseJSON;
        	if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#addClassStatus').html('');
	        			jQuery('#class_name_error').html(datos['errors']['full_name']);
	        			//jQuery('#full_name').attr('style','border:1px solid red;');
	        			jQuery('#class_name').addClass('input-border-error');
	        		}
        		}else
        			jQuery('#addClassStatus').html('Saved');
        		
       		}else
       			jQuery('#addClassStatus').html('Error. Please try again.');
        }
		});
	});





	jQuery('#dashboard-main-content-area').on('click','#addClass',function(){
		lock = 1;
		curCardX = 0;
		clearDefaults();
		jQuery('#addClassStatus').html('Saving data...');
		jQuery('#class_name_error').html('');
        jQuery('#class_name').removeClass('input-border-error');
        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'full_name' : jQuery('#class_name').val()};

		jQuery.ajax({
        url: '/fcard/users/addClass',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	var datos = data.responseJSON;
        	if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#addClassStatus').html('');
	        			jQuery('#class_name_error').html(datos['errors']['full_name']);
	        			//jQuery('#full_name').attr('style','border:1px solid red;');
	        			jQuery('#class_name').addClass('input-border-error');
	        		}
        		}else
        			jQuery('#addClassStatus').html('Saved');
        		
       		}else
       			jQuery('#addClassStatus').html('Error. Please try again.');
        }
		});
	});
	



	jQuery('#dashboard-main-content-area').on('click','#addDeck',function(){
		lock = 1;
		curCardX = 0;
		clearDefaults();
		jQuery('#addDeckStatus').html('Saving data...');
		jQuery('#deck_name_error').html('');
        jQuery('#deck_name').removeClass('input-border-error');
        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'full_name' : jQuery('#deck_name').val()};

		jQuery.ajax({
        url: '/fcard/users/addDeck',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	var datos = data.responseJSON;
        	if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#addDeckStatus').html('');
	        			jQuery('#deck_name_error').html(datos['errors']['full_name']);
	        			//jQuery('#full_name').attr('style','border:1px solid red;');
	        			jQuery('#deck_name').addClass('input-border-error');
	        		}
        		}else
        			jQuery('#addDeckStatus').html('Saved');
        		
       		}else
       			jQuery('#addDeckStatus').html('Error. Please try again.');
        }
		});
	});
		
	
	jQuery('#dashboard-main-content-area').on('click','.editClass',function(e){
		e.preventDefault();
		var thisa = this;
		var classid = parseInt(jQuery(this).attr('classid'));
		if(jQuery(this).html()=='Edit Class'){
			var fullname = jQuery('#class-'+classid).find('td:nth-child(2)').html();
			jQuery('#class-'+classid).find('td:nth-child(2)').html('<input type="text" class="editclass_name" value="'+fullname+'"/>');
			jQuery(this).html('Save');

		}else{

			lock = 1;
			curCardX = 0;
			clearDefaults();

			jQuery(this).html('Saving...');


			var fullname = jQuery('#class-'+classid).find('td:nth-child(2)').find('input').val();


			var datas = {'full_name' : fullname,'class_id' : classid};
			jQuery.ajax({
	        url: '/fcard/users/saveEditClass',
	        data: {'datas': datas},
	        type: 'POST',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){

	        	lock = 0;
	        	var datos = data.responseJSON;
	        	jQuery(thisa).html('Save');
	        	if(datos['status']=='ok'){

	        		if(datos['errors']){
	        			jQuery('#class-'+classid).find('td:nth-child(2)').find('input').addClass('input-border-error');
	        			jQuery(thisa).html('Save');
	        		}else{
	        			jQuery('#class-'+classid).find('td:nth-child(2)').html(fullname);
	        			jQuery(thisa).html('Edit Class');
	        		}


	        	}else{
	        		alert('Error occured. Please try again.');
	        	}

	        }
	    	});





		}
	});




	jQuery('#dashboard-main-content-area').on('change','#sound',function(){


		var valThis = jQuery(this).prop('checked');



		if(valThis==true){
			jQuery(this).val(1);
		}else{
			jQuery(this).val(0);
		}

	});
	

	


	//start
	jQuery('#dashboard-main-content-area').on('click','#saveGameSettingsId',function(){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		var thisa = this;
		jQuery('#saveGameSettingStatus').html('Saving data...');

		jQuery('#sx_error').html('');
		jQuery('#dx_error').html('');
		jQuery('#st_error').html('');
		jQuery('#dt_error').html('');


		jQuery('#sx').removeClass('input-border-error');
		jQuery('#dx').removeClass('input-border-error');
		jQuery('#st').removeClass('input-border-error');
		jQuery('#dt').removeClass('input-border-error');


        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'sx' : jQuery('#sx').val(),'dx' : jQuery('#dx').val(),'st' : jQuery('#st').val(),'dt' : jQuery('#dt').val(),'sound' : jQuery('#sound').val()};



		jQuery.ajax({
        url: '/fcard/users/saveGameSettings',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){

        	lock = 0;

        	var datos = data.responseJSON;
       		


       		if(datos['status']=='ok'){
       			if(datos['errors']){
	        		

	        		if(datos['errors']['sx']){
	        			jQuery('#saveGameSettingStatus').html('');
	        			jQuery('#sx_error').html(datos['errors']['sx']);
	        			jQuery('#sx_error').addClass('input-border-error');
	        		}

	        		if(datos['errors']['dx']){
	        			jQuery('#saveGameSettingStatus').html('');
	        			jQuery('#dx_error').html(datos['errors']['dx']);
	        			jQuery('#dx_error').addClass('input-border-error');
	        		}

	        		if(datos['errors']['st']){
	        			jQuery('#saveGameSettingStatus').html('');
	        			jQuery('#st_error').html(datos['errors']['st']);
	        			jQuery('#st_error').addClass('input-border-error');
	        		}

	        		if(datos['errors']['dt']){
	        			jQuery('#saveGameSettingStatus').html('');
	        			jQuery('#dt_error').html(datos['errors']['dt']);
	        			jQuery('#dt_error').addClass('input-border-error');
	        		}


        		}else
        			jQuery('#saveGameSettingStatus').html('Saved');
       		}else
       			jQuery('#saveEditStudentStatus').html('Error. Please try again.');
       		
        	
     

        }
		});


	});


	//end



	jQuery('#dashboard-main-content-area').on('click','#saveEditStudent',function(){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		var thisa = this;
		var stid = parseInt(jQuery(this).attr('studentid'));
		jQuery('#saveEditStudentStatus').html('Saving data...');


		jQuery('#student_name_error').html('');
		jQuery('#student_sx_error').html('');
		jQuery('#student_dx_error').html('');
		jQuery('#student_st_error').html('');
		jQuery('#student_dt_error').html('');


		jQuery('#student_name').removeClass('input-border-error');
		jQuery('#student_sx').removeClass('input-border-error');
		jQuery('#student_dx').removeClass('input-border-error');
		jQuery('#student_st').removeClass('input-border-error');
		jQuery('#student_dt').removeClass('input-border-error');


        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'full_name' : jQuery('#student_name').val(),'sx' : jQuery('#student_sx').val(),'dx' : jQuery('#student_dx').val(),'st' : jQuery('#student_st').val(),'dt' : jQuery('#student_dt').val(),'student_id' : stid};





		jQuery.ajax({
        url: '/fcard/users/saveEditStudent',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){

        	lock = 0;

        	var datos = data.responseJSON;
       		


       		if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#saveEditStudentStatus').html('');
	        			jQuery('#student_name_error').html(datos['errors']['full_name']);
	        			jQuery('#student_name').addClass('input-border-error');
	        		}

	        		if(datos['errors']['sx']){
	        			jQuery('#saveEditStudentStatus').html('');
	        			jQuery('#student_sx_error').html(datos['errors']['sx']);
	        			jQuery('#student_sx_error').addClass('input-border-error');
	        		}

	        		if(datos['errors']['dx']){
	        			jQuery('#saveEditStudentStatus').html('');
	        			jQuery('#student_dx_error').html(datos['errors']['dx']);
	        			jQuery('#student_dx_error').addClass('input-border-error');
	        		}

	        		if(datos['errors']['st']){
	        			jQuery('#saveEditStudentStatus').html('');
	        			jQuery('#student_st_error').html(datos['errors']['st']);
	        			jQuery('#student_st_error').addClass('input-border-error');
	        		}

	        		if(datos['errors']['dt']){
	        			jQuery('#saveEditStudentStatus').html('');
	        			jQuery('#student_dt_error').html(datos['errors']['dt']);
	        			jQuery('#student_dt_error').addClass('input-border-error');
	        		}


        		}else
        			jQuery('#saveEditStudentStatus').html('Saved');
        		
       		}else
       			jQuery('#saveEditStudentStatus').html('Error. Please try again.');
       		
        	
     

        }
		});


	});






	jQuery('#dashboard-main-content-area').on('click','#saveProfileDashboard',function(){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		jQuery('#saveStatus').html('Saving data...');
		jQuery('#full_name_error').html('');
        jQuery('#full_name').removeClass('input-border-error');
        //jQuery('#full_name').attr('style','border:1px solid black;');
		var datas = {'full_name' : jQuery('#full_name').val()};



		jQuery.ajax({
        url: '/fcard/users/saveProfileDashboard',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){

        	lock = 0;

        	var datos = data.responseJSON;
       		


       		if(datos['status']=='ok'){
       			if(datos['errors']){
	        		if(datos['errors']['full_name']){
	        			jQuery('#saveStatus').html('');
	        			jQuery('#full_name_error').html(datos['errors']['full_name']);
	        			//jQuery('#full_name').attr('style','border:1px solid red;');
	        			jQuery('#full_name').addClass('input-border-error');
	        		}
        		}else
        			jQuery('#saveStatus').html('Saved');
        		
       		}else
       			jQuery('#saveStatus').html('Error. Please try again.');
       		
        	
       		

        }
		});


	});
	


jQuery('#fcard-menu').on('click','.quickInputMistakeLog',function(e){
		lock = 1;
		clearDefaults();
		curCardX = 0;
		e.preventDefault();


		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/quickInputMistakeLog").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
		
});




	
	jQuery('#fcard-menu').on('click','.viewProfile',function(e){
		lock = 1;
		clearDefaults();
		curCardX = 0;
		e.preventDefault();
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewProfile").done(
			function( data ){
				lock = 0;

				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});



	
	jQuery("#dashboard-main-content-area").on('click','#filterDeck',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		var thisa = this;

		var valIt = jQuery(this).val();


		jQuery(this).val('Filtering..');
		jQuery(this).addClass('disabled');



		var deckSelect = parseInt(jQuery('#deckselect').val());


		var datas = {'user_id' : deckSelect}
		jQuery.ajax({
        url: '/fcard/users/filterDeck',
        data: {'datas': datas},
        type: 'GET',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	data = data.responseText;
        	jQuery(thisa).val(valIt);
        	jQuery(thisa).removeClass('disabled');
        	jQuery('#managedecktable').html(data);
        }


    	});

	});



	jQuery("#dashboard-main-content-area").on('click','#filterClass',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		var thisa = this;

		var valIt = jQuery(this).val();


		jQuery(this).val('Filtering..');
		jQuery(this).addClass('disabled');



		var classSelect = parseInt(jQuery('#classselect').val());


		var datas = {'user_id' : classSelect}
		jQuery.ajax({
        url: '/fcard/users/filterClass',
        data: {'datas': datas},
        type: 'GET',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	data = data.responseText;
        	jQuery(thisa).val(valIt);
        	jQuery(thisa).removeClass('disabled');
        	jQuery('#manageclasstable').html(data);
        }


    	});

	});


	

	
	jQuery("#dashboard-main-content-area").on('click','#filterStandardMistakeLog',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		var thisa = this;

		var valIt = jQuery(this).val();


		jQuery(this).val('Filtering..');
		jQuery(this).addClass('disabled');



		var logSelect = parseInt(jQuery('#logselect').val());


		var datas = {'user_id' : logSelect}
		jQuery.ajax({
        url: '/fcard/users/filterStandardMistakeLog',
        data: {'datas': datas},
        type: 'GET',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	data = data.responseText;
        	jQuery(thisa).val(valIt);
        	jQuery(thisa).removeClass('disabled');
        	jQuery('#managelogtable').html(data);
        }


    	});

	});

	

	jQuery("#dashboard-main-content-area").on('click','#filterInputMistakeLog',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		var thisa = this;

		var valIt = jQuery(this).val();


		jQuery(this).val('Filtering..');
		jQuery(this).addClass('disabled');



		var logSelect = parseInt(jQuery('#logselect').val());


		var datas = {'user_id' : logSelect}
		jQuery.ajax({
        url: '/fcard/users/filterInputMistakeLog',
        data: {'datas': datas},
        type: 'GET',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	data = data.responseText;
        	jQuery(thisa).val(valIt);
        	jQuery(thisa).removeClass('disabled');
        	jQuery('#managelogtable').html(data);
        }


    	});

	});

	jQuery("#dashboard-main-content-area").on('click','#filterLog',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		var thisa = this;

		var valIt = jQuery(this).val();


		jQuery(this).val('Filtering..');
		jQuery(this).addClass('disabled');



		var logSelect = parseInt(jQuery('#logselect').val());


		var datas = {'user_id' : logSelect}
		jQuery.ajax({
        url: '/fcard/users/filterLog',
        data: {'datas': datas},
        type: 'GET',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	lock = 0;
        	data = data.responseText;
        	jQuery(thisa).val(valIt);
        	jQuery(thisa).removeClass('disabled');
        	jQuery('#managelogtable').html(data);
        }


    	});

	});


	jQuery("#fcard-menu").on('click','.quick-review-logs',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/quickReviewLog").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});



	jQuery("#dashboard-main-content-area").on('click','.editStudent',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;

		var studentid = parseInt(jQuery(this).attr('studentid'));



		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewEditStudent?studentid="+studentid).done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});

	

	jQuery("#dashboard-main-content-area").on('click','.manage-classes',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/manageClasses").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});



	jQuery("#fcard-menu").on('click','.manage-decks',function(e){
		lock = 1;
		e.preventDefault();
		clearDefaults();
		curCardX = 0;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/manageDecks").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});


	jQuery("#fcard-menu").on('click','.viewGameMode',function(e){
		lock = 1;
		clearDefaults();
		curCardX = 0;
		e.preventDefault();
		viewGameMode();
	});




	jQuery('#dashboard-main-content-area').on('click','#addCard',function(e){
		lock = 1;
		clearDefaults();
		curCardX = 0;

		e.preventDefault();
		var thisa = this;
		jQuery(this).html('Adding Card...');
		//jQuery('#addCardStatus').html('Adding Card...');
		//jQuery('#card_question_error').html('');
        jQuery('.addCardTd[isactive="1"]').find('#card_question').removeClass('input-border-error');
        //jQuery('#card_question_notes_error').html('');
        jQuery('.addCardTd[isactive="1"]').find('#card_question_notes').removeClass('input-border-error');
        //jQuery('#card_answer_error').html('');
        jQuery('.addCardTd[isactive="1"]').find('#card_answer').removeClass('input-border-error');
        //jQuery('#card_answer_notes_error').html('');
        jQuery('.addCardTd[isactive="1"]').find('#card_answer_notes').removeClass('input-border-error');



        //alert(jQuery('#card_answer_notes').val());
		var datas = {'question' : jQuery('.addCardTd[isactive="1"]').find('#card_question').val(),
					'question_notes' : jQuery('.addCardTd[isactive="1"]').find('#card_question_notes').val(),
					'answer' : jQuery('.addCardTd[isactive="1"]').find('#card_answer').val(),
					'answer_notes' : jQuery('.addCardTd[isactive="1"]').find('#card_answer_notes').val(),
					'deck_id' : jQuery(this).attr('deckid')};


		jQuery.ajax({
        url: '/fcard/users/addCard',
        data: {'datas': datas},
        type: 'POST',
        dataType: 'json',
        cache:false, 
        complete : function(data, textStatus, jqXHR){
        	var datos = data.responseJSON;

        	lock = 0;

        	if(datos['status']=='ok'){


       			if(datos['errors']){
       				jQuery(thisa).html('Add Card');
       				var errorsList = ['question','question_notes','answer','answer_notes'];

       				jQuery.each(errorsList,function(key,value){

	       				if(datos['errors'][value]){
		        			//jQuery('#addCardStatus').html('');
		        			//jQuery('.addCardTd').find('#card_'+value+'_error').html(datos['errors'][value]);
		        			//jQuery('#full_name').attr('style','border:1px solid red;');
		        			jQuery('.addCardTd[isactive="1"]').find('#card_'+value).addClass('input-border-error');
		        		}
       				});

        		}else{
        			//jQuery(thisa).parent().html('<span><a href="#" class="edit-card-now" editid="'+datos['card']['id']+'" >Edit</a></span> <span><a href="#" class="delete-card-now" deleteid="'+datos['card']['id']+'">Delete</a></span>');
        			jQuery('.addCardTd[isactive="1"]').find('td:nth-child(1)').html(datos['card']['id']);
        			jQuery('.addCardTd[isactive="1"]').find('td:nth-child(2)').html('<strong>Q</strong> - '+ datos['card']['question'] + '<br/>' + '<strong>N</strong> - '+datos['card']['question_notes']);
        			
        			jQuery('.addCardTd[isactive="1"]').find('td:nth-child(3)').html('<strong>A</strong> - '+ datos['card']['answer'] + '<br/>' + '<strong>N</strong> - '+datos['card']['answer_notes']);
        			jQuery('.addCardTd[isactive="1"]').find('td:nth-child(3)').removeAttr('colspan');
        			jQuery('.addCardTd[isactive="1"]').find('td:nth-child(4)').html('<table><thead><tr><th style="text-align:center;">Slow</th><th style="text-align:center;">Fast</th></tr></thead><tbody><tr><td style="text-align:center;"><button class="btn btn-primary" onclick="startRecording(this,\'slow_q\','+datos['card']['id']+');">Record</button><button class="btn btn-warning" onclick="stopRecording(this,\'slow_q\','+datos['card']['id']+');" disabled="">Stop</button></td><td><button class="btn btn-primary" onclick="startRecording(this,\'fast_q\','+datos['card']['id']+');">Record</button><button class="btn btn-warning" onclick="stopRecording(this);" disabled="">Stop</button></td></tr></tbody></table>');
        			jQuery('.addCardTd[isactive="1"]').append('<td><table><thead><tr><th style="text-align:center;">Slow</th><th style="text-align:center;">Fast</th></tr></thead><tbody><tr><td style="text-align:center;"><button class="btn btn-primary" onclick="startRecording(this,\'slow_a\','+datos['card']['id']+');">Record</button><button class="btn btn-warning" onclick="stopRecording(this,\'slow_a\','+datos['card']['id']+');" disabled="">Stop</button></td><td style="text-align:center;"><button class="btn btn-primary" onclick="startRecording(this,\'fast_a\','+datos['card']['id']+');">Record</button><button class="btn btn-warning" onclick="stopRecording(this,\'fast_a\','+datos['card']['id']+');" disabled="">Stop</button></td></tr></tbody></table></td>');
        			jQuery('.addCardTd[isactive="1"]').append('<td><span><a href="#" class="edit-card-now" editid="'+datos['card']['id']+'" >Edit</a></span> <span><a href="#" class="delete-card-now" deleteid="'+datos['card']['id']+'">Delete</a></span></td>');
        			

        			var t = setInterval(function(){
	        			if(jQuery('#addCardTd').length>0){
	        				clearInterval(t);
	        				jQuery('.addCardTd[isactive]').attr('id','card-'+datos['card']['id']);
	        				if(jQuery('.addCardTd').length>0){
	        					var tx = setInterval(function(){
	        					if(jQuery('addCardTd[isactive]')){

	        						jQuery('#card-'+datos['card']['id']).removeAttr('isactive');
	        					
	        						jQuery('.deck-container').append('<tr class="addCardTd" id="addCardTd" isactive="1"><td></td><td><strong>Q</strong> - <textarea id="card_question" style="height:160px;width:100%;"></textarea><br/><strong>N</strong><textarea id="card_question_notes" style="height:160px;width:100%;"></textarea></td><td colspan=3><strong>A</strong> - <textarea id="card_answer" style="height:160px;width:100%;"></textarea><br/><strong>N</strong><textarea id="card_answer_notes" style="height:160px;width:100%;"></textarea></td><td><a href="#" id="addCard" deckid="'+datos['card']['deck_id']+'">Add Card</a></td></td></tr>');
	        						clearInterval(tx);
	        					}

	        					},100);
	        				
	        				}
	        				
	        				
	        			}else{
	        			}
        			},100);
        		}
        		
       		}else
       			alert('Error. Please try again.');
       			//jQuery('#addCardStatus').html('Error. Please try again.');
        }
		});
	});

	

	jQuery("#dashboard-main-content-area").on('click','.add-class-now-filter',function(e){
		var userid = parseInt(jQuery(this).attr('userid'));
		addClassNowFilter(e,userid);
	});


	jQuery("#dashboard-main-content-area").on('click','.add-deck-now-filter',function(e){
		var userid = parseInt(jQuery(this).attr('userid'));

		addDeckNowFilter(e,userid);
	});
	
	jQuery("#fcard-menu").on('click','.add-class',function(e){
		lock = 1;
		clearDefaults();
		curCardX = 0;
		e.preventDefault();
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewAddClass").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});



	

	jQuery("#fcard-menu").on('click','.add-student',function(e){
		addStudentNow(e);
	});


	

	jQuery("#fcard-menu").on('click','.add-teacher',function(e){
		addTeacherNow(e);
	});







	jQuery("#fcard-menu").on('click','.add-deck',function(e){
		lock = 1;
		clearDefaults();
		curCardX = 0;
		e.preventDefault();
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewAddDeck").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});

	jQuery('#dashboard-main-content-area').on('click','.add-deck-now',function(e){
		clearDefaults();
		curCardX = 0;
		e.preventDefault();
		lock = 1;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/viewAddDeck").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});


	jQuery("#dashboard-main-content-area").on('click','.manage-decks',function(e){
		clearDefaults();
		curCardX = 0;
		e.preventDefault();
		lock = 1;
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		jQuery.get("/fcard/users/manageDecks").done(
			function( data ){
				lock = 0;
				jQuery('#dashboard-main-content-area').html(data);
			}
		);
	});


	jQuery('#dashboard-main-content-area').on('click','.deleteTeacher',function(e){

		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to delete this teacher?');

		if(r==true){
			var thisa = this;
			var teacherid = jQuery(this).attr('teacherid');
			var datas = {'student_id' : teacherid};
			jQuery(this).html('Deleting...');
			jQuery.ajax({
	        url: '/fcard/users/deleteTeacher',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;

	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#teacher-'+teacherid).remove();
	        	}else{
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}


	});






	jQuery('#dashboard-main-content-area').on('click','.approveStudent',function(e){

		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to approve this Student?');

		if(r==true){
			var thisa = this;
			var studentid = jQuery(this).attr('studentid');
			var datas = {'student_id' : studentid};


			jQuery(this).html('Approving Student...');
			jQuery.ajax({
	        url: '/fcard/users/approveStudent',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;
	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery(thisa).removeClass('approveStudent');
	        		jQuery(thisa).addClass('unapproveStudent');
	        		jQuery(thisa).html('Unapprove Student');
	        		jQuery('#student-'+studentid).find('td:nth-child(5)').html('Approved');
	        		jQuery('#student-'+studentid).find('td:nth-child(5)').attr('style','color:green;');
	        	}else{
	        		jQuery(thisa).html('Approve Student');
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}


	});


	jQuery('#dashboard-main-content-area').on('click','.unapproveStudent',function(e){

		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to unapprove this Student?');

		if(r==true){
			var thisa = this;
			var studentid = jQuery(this).attr('studentid');
			var datas = {'student_id' : studentid};


			jQuery(this).html('Unapproving Student...');
			jQuery.ajax({
	        url: '/fcard/users/unapproveStudent',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;
	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery(thisa).removeClass('unapproveStudent');
	        		jQuery(thisa).addClass('approveStudent');
	        		jQuery(thisa).html('Approve Student');
	        		jQuery('#student-'+studentid).find('td:nth-child(5)').html('Pending');
	        		jQuery('#student-'+studentid).find('td:nth-child(5)').attr('style','color:red;');
	        	}else{
	        		jQuery(thisa).html('Unapprove Student');
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}


	});


	


	jQuery('#dashboard-main-content-area').on('click','.deleteStudent',function(e){

		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to delete this student?');

		if(r==true){
			var thisa = this;
			var studentid = jQuery(this).attr('studentid');
			var datas = {'student_id' : studentid};
			jQuery(this).html('Deleting...');
			jQuery.ajax({
	        url: '/fcard/users/deleteStudent',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;
	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#student-'+studentid).remove();
	        	}else{
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}


	});






	jQuery('#dashboard-main-content-area').on('click','.deleteClass',function(e){

		e.preventDefault();
		lock = 1;
		curCardX = 0;
		clearDefaults();
		var r = confirm('Are you sure you want to delete this class?');

		if(r==true){
			var thisa = this;
			var classid = jQuery(this).attr('classid');
			var datas = {'class_id' : classid};
			jQuery(this).html('Deleting...');
			jQuery.ajax({
	        url: '/fcard/users/deleteClass',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;

	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#class-'+classid).remove();
	        	}else{
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	
		}


	});

	
	jQuery('#dashboard-main-content-area').on('click','.removefromclassdeck',function(e){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		e.preventDefault();

		var thisa = this;



		var r = confirm('Are you sure you want to remove this deck from this class?');


		if(r==true){
			
			var removeid = parseInt(jQuery(thisa).attr('removeid'));
			var classid = parseInt(jQuery(thisa).attr('classid'));


			var datas = {'class_id' : classid,'deck_id' : removeid};
			jQuery(this).html('Removing...');
			jQuery.ajax({
	        url: '/fcard/users/removeDeckFromClass',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;

	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#deck-'+removeid).remove();
	        	}else{
	        		jQuery(thisa).html('Remove Deck from this class');
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	





		}





	});




	jQuery('#dashboard-main-content-area').on('click','.removefromclass',function(e){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		e.preventDefault();

		var thisa = this;



		var r = confirm('Are you sure you want to remove this student from this class?');


		if(r==true){
			
			var removeid = parseInt(jQuery(thisa).attr('removeid'));
			var classid = parseInt(jQuery(thisa).attr('classid'));



			var datas = {'class_id' : classid,'student_id' : removeid};
			jQuery(this).html('Removing...');
			jQuery.ajax({
	        url: '/fcard/users/removeStudentFromClass',
	        data: {'datas': datas},
	        type: 'GET',
	        dataType: 'json',
	        cache:false, 
	        complete : function(data, textStatus, jqXHR){
	        	lock = 0;

	        	var datos = data.responseJSON;
	        	if(datos['status']=='ok'){
	        		jQuery('#student-'+removeid).remove();
	        	}else{
	        		jQuery(thisa).html('Remove Student from this class');
	        		alert('Error. Please try again.');
	        	}
	        	
	        }
	    	});	





		}





	});
	

	

	jQuery('#dashboard-main-content-area').on('click','.viewClassDeckInfo',function(e){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		e.preventDefault();
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		var classid = jQuery(this).attr('classid');

		jQuery.ajax({
        url: '/fcard/users/viewClassDeckProfile',
        data: {'classid': classid},
        type: 'GET',
        cache:false, 
        complete : function(data, textStatus, jqXHR){

        	lock = 0;

        	var datos = data.responseText;
       		
        	if(datos=='not ok')
        		jQuery('#dashboard-main-content-area').html('Error. Please try again.');
        	else{
        		//jQuery(datos).find('.addCardTd').attr('id','addCardTd-'+addCardProfile);
        		jQuery('#dashboard-main-content-area').html(datos);
        		
        	}


       	
        	
       		

        }
		});



	});


	jQuery('#dashboard-main-content-area').on('click','.viewClassInfo',function(e){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		e.preventDefault();
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		var classid = jQuery(this).attr('classid');

		jQuery.ajax({
        url: '/fcard/users/viewClassProfile',
        data: {'classid': classid},
        type: 'GET',
        cache:false, 
        complete : function(data, textStatus, jqXHR){

        	lock = 0;

        	var datos = data.responseText;
       		
        	if(datos=='not ok')
        		jQuery('#dashboard-main-content-area').html('Error. Please try again.');
        	else{
        		//jQuery(datos).find('.addCardTd').attr('id','addCardTd-'+addCardProfile);
        		jQuery('#dashboard-main-content-area').html(datos);
        		
        	}


       	
        	
       		

        }
		});



	});




	jQuery('#dashboard-main-content-area').on('click','.viewDeckInfo',function(e){
		clearDefaults();
		lock = 1;
		curCardX = 0;
		e.preventDefault();
		jQuery('#dashboard-main-content-area').html('<div style="text-align:center;"><img src="http://freememorialwebsites.com/fcard/webroot/img/ajax-loader.gif" /></div>');
		var deckid = jQuery(this).attr('deckid');

		jQuery.ajax({
        url: '/fcard/users/viewDeckProfile',
        data: {'deckid': deckid},
        type: 'GET',
        cache:false, 
        complete : function(data, textStatus, jqXHR){

        	lock = 0;

        	var datos = data.responseText;
       		
        	if(datos=='not ok')
        		jQuery('#dashboard-main-content-area').html('Error. Please try again.');
        	else{
        		//jQuery(datos).find('.addCardTd').attr('id','addCardTd-'+addCardProfile);
        		jQuery('#dashboard-main-content-area').html(datos);
        		
        	}


       	
        	
       		

        }
		});



	});



});
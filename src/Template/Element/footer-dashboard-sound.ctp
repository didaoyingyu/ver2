<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?php if(isset($curPage) && $curPage=='register') : ?>
    <?= $this->Html->script('custom.js'); ?>
    <?php endif; ?>
    <?php if(isset($curPage) && $curPage=='dashboard') : ?>
    <?php
        //$scriptsArray = ['app/js/ACFIRFilter.js','app/js/ACAAFilter.js','app/js/ACSpectrum.js','app/js/ACFFT.js','app/js/SpectrumWorker.js','app/js/SpectrumDisplay.js','app/js/audioplayback.js','app/js/filedropbox.js','app/js/fft.js','app/js/audioLayerControl.js','app/js/audiosequence.js','app/js/AudioSequenceEditor.js','app/js/mathutilities.js','app/js/wavetrack.js','app/js/binarytoolkit.js','app/js/filesystemutility.js','app/js/editorapp.js','lib/recorder.js','recordLive.js','drone.js'];

        $scriptsArray = ['recordmp3.js'];



        foreach($scriptsArray as $script){
            echo $this->Html->script($script);
        }


    ?>
    <?= $this->Html->script('jquery.tokeninput.js'); ?>
    <?= $this->Html->script('dashboard.js'); ?>
    

    <?php endif; ?>
    <?= $this->Html->script('https://cdn.rawgit.com/nnattawat/flip/v1.0.16/dist/jquery.flip.min.js') ?>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <?= $this->Html->script('ie10-viewport-bug-workaround.js') ?>
  </body>
</html>
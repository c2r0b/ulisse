var annyang = require('annyang');
require('speechkitt');

if (annyang) {
  // Add our commands to annyang
  annyang.addCommands({
    'ciao': function() { alert('Hello world!'); }
  });

  // Tell KITT to use annyang
  SpeechKITT.annyang();
  SpeechKITT.setStartCommand(annyang.start);

  // annyang config and deploy
  annyang.setLanguage('it-IT');

  // Define a stylesheet for KITT to use
  SpeechKITT.setStylesheet('../stylesheets/speech_assistant.css');

  // SpeechKITT interface
  SpeechKITT.setToggleLabelText("Attiva controllo vocale");
  SpeechKITT.setInstructionsText("Come posso aiutarti?");

  // SpeechKITT UX settings
  SpeechKITT.rememberStatus(120);

  // Render KITT's interface
  SpeechKITT.vroom();
}

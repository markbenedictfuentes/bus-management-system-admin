<?php

require __DIR__ . '/../../vendor/autoload.php';

use Phpml\Classification\NaiveBayes;
use Phpml\ModelManager;

// Initialize classifier
$classifier = new NaiveBayes();

// Expanded training data with 40 examples per category

// --- Behavior Complaints (40 examples) ---
$behaviorSamples = [
    ['Driver was rude and aggressive'],
    ['The driver yelled at passengers'],
    ['Ang driver ay bastos at walang modo'],
    ['Sinigawan kami ng driver nang walang dahilan'],
    ['Driver did not respect passengers'],
    ['The driver was cursing loudly'],
    ['Nagmumura ang driver at hindi magalang'],
    ['The driver was arguing with a passenger'],
    ['Masyadong mainit ang ulo ng driver'],
    ['Driver ignored traffic rules and was reckless'],
    ['The driver was driving too aggressively'],
    ['Parang nagmamadali masyado ang driver at delikado'],
    ['Driver refused to stop at designated bus stops'],
    ['Hindi huminto ang driver sa tamang hintuan'],
    ['The driver was using his phone while driving'],
    ['Ginagamit ng driver ang cellphone niya habang nagmamaneho'],
    ['Driver did not listen to passenger concerns'],
    ['Sinita na siya pero hindi siya nakinig'],
    ['Nag-aaway ang driver at konduktor'],
    ['The driver was eating while driving'],
    ['The driver was distracted and careless'],
    ['Ang driver ay hindi nakatingin sa daan'],
    ['Driver ignored safety protocols'],
    ['Hindi pinansin ng driver ang safety measures'],
    ['The driver was in a bad mood and scolded everyone'],
    ['Galit ang driver at pinagalitan ang mga pasahero'],
    ['Driver abruptly slammed the brakes'],
    ['Biglang pinrino ng driver ang preno nang walang babala'],
    ['Driver did not maintain a safe distance from the car ahead'],
    ['Hindi sinunod ng driver ang tamang distansya'],
    ['The driver was texting while driving'],
    ['Ang driver ay walang disiplina'],
    ['Driver was erratic'],
    ['The driver behaved unprofessionally'],
    ['Hindi magalang ang driver'],
    ['Driver had a short temper'],
    ['Driver made passengers uncomfortable'],
    ['The driver was careless'],
    ['Driver did not follow protocol'],
    ['The driver acted inappropriately']
];

// --- Cleanliness Complaints (40 examples) ---
$cleanlinessSamples = [
    ['Bus was very dirty and smelled bad'],
    ['The bus smelled musty and damp'],
    ['Amoy sigarilyo at pawis sa loob ng bus'],
    ['The bus had trash everywhere'],
    ['The seats were torn and stained'],
    ['The bus had a foul odor and was unclean'],
    ['Amoy ihi at pawis ang loob ng bus'],
    ['The floor was sticky and dirty'],
    ['Napakadulas at madumi ng sahig ng bus'],
    ['The air conditioning was broken and it smelled bad'],
    ['Sira ang aircon at ang init sa loob'],
    ['There were cockroaches inside the bus'],
    ['May mga ipis sa loob ng bus!'],
    ['The bus windows were too dirty to see through'],
    ['Hindi makita ang labas dahil sobrang dumi ng bintana'],
    ['The bus had food wrappers and empty bottles lying around'],
    ['Nagkalat ang basura sa loob ng bus'],
    ['The bus smelled like smoke and sweat'],
    ['Amoy sigarilyo at pawis sa loob ng bus'],
    ['The bus floors were not cleaned for weeks'],
    ['Hindi linis ang sahig ng bus sa ilang linggo'],
    ['The seats were covered in stains'],
    ['May mantsa sa bawat upuan'],
    ['Trash was piled up in the corners of the bus'],
    ['Nakalat ang basura sa mga sulok ng bus'],
    ['The bus interior was poorly maintained'],
    ['Hindi naayos ang loob ng bus'],
    ['There was a persistent odor that lingered in the bus'],
    ['May amoy na hindi naalis kahit ilang beses nang nilinis'],
    ['The bus smelled musty and damp'],
    ['Mabaho at amoy amag ang bus'],
    ['The bus floor had gum and sticky residues'],
    ['May mga malagkit na tira sa sahig ng bus'],
    ['The bus windows were streaked with grime'],
    ['Malabo ang bintana dahil sa alikabok'],
    ['The bus had spilled liquids on the floor'],
    ['May mga natapong likido sa loob ng bus'],
    ['The bus did not have proper cleaning routines'],
    ['Walang regular na linis sa bus']
];

// --- Punctuality Complaints (40 examples) ---
$punctualitySamples = [
    ['Bus was very late and inconsistent'],
    ['Sobrang tagal dumating ng bus'],
    ['The bus did not arrive on time'],
    ['Hindi dumating sa oras ang bus'],
    ['The bus schedule was incorrect and inconsistent'],
    ['Mali-mali ang iskedyul ng bus'],
    ['Bus left earlier than scheduled'],
    ['Umalis ang bus bago pa ang tamang oras'],
    ['The bus was delayed for more than an hour'],
    ['Higit isang oras kaming naghintay sa bus'],
    ['The bus skipped several scheduled stops'],
    ['Hindi huminto ang bus sa dapat nitong hintuan'],
    ['The bus kept stopping for too long at each station'],
    ['Sobrang tagal ng bus sa bawat hintuan'],
    ['The bus was frequently delayed'],
    ['Laging late dumadating ang bus'],
    ['The bus had no clear arrival time'],
    ['Walang malinaw na oras ng dating ang bus'],
    ['Bus drivers don’t follow the schedule properly'],
    ['Hindi sinusunod ng driver ang tamang iskedyul'],
    ['The bus was cancelled without notice'],
    ['Kinansela ang bus nang walang abiso'],
    ['The bus had a significant delay due to traffic'],
    ['Dahil sa traffic, malaki ang delay ng bus'],
    ['There was no information about delays'],
    ['Walang impormasyon tungkol sa pagka-antala ng bus'],
    ['The bus departed long after the scheduled time'],
    ['Umalis ang bus nang malayo sa oras na nakatakda'],
    ['Passengers were not informed about the delay'],
    ['Hindi sinabihan ang mga pasahero tungkol sa delay'],
    ['The bus route was changed last minute'],
    ['Biglang nagbago ang ruta ng bus nang walang paunang abiso'],
    ['The bus experienced unexpected delays'],
    ['Nagkaroon ng hindi inaasahang delay ang bus'],
    ['The bus did not stop at the right time'],
    ['Hindi tumigil ang bus sa tamang oras'],
    ['The bus schedule was constantly shifting'],
    ['Palaging nagbabago ang iskedyul ng bus'],
    ['The bus was off schedule entirely'],
    ['Wala talagang ayos ang iskedyul ng bus']
];

// Pagsamahin ang lahat ng samples at labels
$samples = array_merge($behaviorSamples, $cleanlinessSamples, $punctualitySamples);
$labels = array_merge(
    array_fill(0, count($behaviorSamples), 'Behavior'),
    array_fill(0, count($cleanlinessSamples), 'Cleanliness'),
    array_fill(0, count($punctualitySamples), 'Punctuality')
);

// Train the model
$classifier->train($samples, $labels);

// Save the trained model
$modelManager = new ModelManager();
$modelManager->saveToFile($classifier, __DIR__ . '/complaint-classifier.model');

echo "Model training complete! complaint-classifier.model has been updated with even more data.";

?>

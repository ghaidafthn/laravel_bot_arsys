<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\Models\Posting;
use App\Models\ArsysResearch;
use App\Models\ArsysResearchRemark;
use App\Models\ArsysDefenseExaminerPresence;
use App\Models\ArsysResearchSupervisor;
use App\Models\ArsysStaff;
use App\Models\ArsysTelegram;

class BotTelegramController extends Controller
{
    public function sendMessage()
    {
        
        $supervisorId = ArsysTelegram::where('chat_id', $chat_id)->value('supervisor_id');

        $researches = ArsysResearch::whereHas('supervisors', function ($query) use ($supervisorId) {
            $query->where('supervisor_id', $supervisorId);
        })->where('milestone_id', '3')->get();
        // Ambil data dari database
        if ($researches->isEmpty()) {
            // Jika tidak ada proposal
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "No proposals were submitted.",
            ]);
        }
        // Format data ke dalam pesan
        $message = "Here's the proposal's data:\n";
        foreach ($researches as $item) {
            $message .= "- {$item->title}: {$item->file} Status : {$item->milestone_id} \n";
        }
        
        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => $message,
        ]);

        $defenses = ArsysResearch::where('milestone_id', '5')->get();
        if ($defenses->isEmpty()) {
            // Jika tidak ada proposal
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "No predefense were submitted.",
            ]);
        }
        // Format data ke dalam pesan
        $pesan = "Here's the predefense's data::\n";
        foreach ($defenses as $sidang) {
            $pesan .= "- {$sidang->id}: {$sidang->title}\n";
        }
        
        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => $pesan,
        ]);

        $yudisiums = ArsysResearch::where('milestone_id', '11')->get();
        if ($yudisiums->isEmpty()) {
            // Jika tidak ada proposal
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "No yudisium were submitted.",
            ]);
        }
        // Format data ke dalam pesan
        $reply = "Here's the yudisium's data:\n";
        foreach ($yudisiums as $yudisium) {
            $reply .= "- {$yudisium->id}: {$yudisium->title}\n";
        }
        
        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => $reply,
        ]);

        // $remarks = ArsysResearch::where('milestone_id', '3')->get();
        // if ($remarks->isEmpty()) {
        //     // Jika tidak ada proposal
        //     return Telegram::sendMessage([
        //         'chat_id' => $chat_id,
        //         'text' => "No proposal's were submitted.",
        //     ]);
        // }
        // // Format data ke dalam pesan
        // $send = "Here's the proposal's data:\n";
        // foreach ($remarks as $remark) {
        //     $send .= "- {$remark->id}: {$remark->title}\n";
        // }
        
        // Telegram::sendMessage([
        //     'chat_id' => $chat_id,
        //     'text' => $send,
        // ]);
//score
        // $scores = ArsysResearch::where('milestone_id', '9')->get();
        // if ($scores->isEmpty()) {
        //     // Jika tidak ada proposal
        //     return Telegram::sendMessage([
        //         'chat_id' => $chat_id,
        //         'text' => "No predefense were done.",
        //     ]);
        // }
        // // Format data ke dalam pesan
        // $kirim = "Here's the predefense's data:\n";
        // foreach ($scores as $skor) {
        //     $kirim .= "- {$skor->id}: {$skor->title}\n";
        // }
        
        // Telegram::sendMessage([
        //     'chat_id' => $chat_id,
        //     'text' => $kirim,
        // ]);
    }





    public function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($response);
    }






    public function commandHandlerWebHook(Request $request)
    {  
        // Ambil data dari database
        $researches = ArsysResearch::where('milestone_id', '3')
        ->get();
        foreach ($researches as $item) {
            $message= "- {$item->title}:\n Status: {$item->milestone_id}\n File: {$item->file} \n\n";
        }
        $defenses = ArsysResearch::where('milestone_id', '5')->get();
        foreach ($defenses as $sidang) {
            $pesan= "- {$sidang->id}:\n {$sidang->title}\n\n";
        }
        $yudisiums = ArsysResearch::where('milestone_id', '11')->get();
        foreach ($yudisiums as $yudisium) {
            $reply= "- {$yudisium->id}:\n {$yudisium->title}\n\n";
        }
        // $remarks = ArsysResearch::where('milestone_id', '3')->get();
        // foreach ($remarks as $remark) {
        //     $send= "- {$remark->id}:\n {$remark->title}\n\n";
        // }
        // $scores = ArsysResearch::where('milestone_id', '9')->get();
        // foreach ($scores as $skor) {
        //     $kirim= "- {$skor->id}:\n {$skor->title}\n\n";
        // }

        $updates = Telegram::commandsHandler(true);
        $chat_id = $updates->getChat()->getId();
        $username = $updates->getChat()->getFirstName();
        $research_file= $updates -> getMessage()->getresearch_file();
        $discussantId = $this->getDiscussantId(); 

        if(strtolower($updates->getMessage()->getText()==='/start')) return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Welcome to ArSys BOT '. $username ."\nPlease click /command to get a list of available commands",
        ]);

        if (strtolower($updates->getMessage()->getText()) === '/command') {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "/proposal to access thesis's proposal data\n/predefense to access thesis trial submission data\n/yudisium to access yudisium's data",
            ]);
        }
        //PROPOSAL
        if (strtolower($updates->getMessage()->getText()) === '/proposal') {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "/request_proposal to check thesis's proposal data\n",
            ]);
        }
        if (strtolower($updates->getMessage()->getText()) === '/request_proposal') {
            $message = "Here is the list of proposals submitted:\n\n";
            foreach ($researches as $item) {
                $message .= "/approve_proposal{$item->id}\n/reject_proposal{$item->id}\n(ID:{$item->id}) - {$item->title}\n(File: {$item->file})\n\n";
            }
            if ($researches->isEmpty()) {
                // Jika tidak ada proposal
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "No proposals were submitted.",
                ]);
            }
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $message,
            ]);
        }

        
if (strpos(strtolower($updates->getMessage()->getText()), '/approve_proposal') === 0) {
    $proposalIdToApprove = substr($updates->getMessage()->getText(), strlen('/approve_proposal'));
    $approvedProposal = ArsysResearch::find($proposalIdToApprove);

    if ($approvedProposal) {
        $approvedProposal->milestone_id = '4'; 
        $approvedProposal->save();

        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Proposals with ID {$proposalIdToApprove} are successfully approved and milestones are changed!",
        ]);
    } else {
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Proposal with ID {$proposalIdToApprove} not found!",
        ]);
    }
}


if (strpos(strtolower($updates->getMessage()->getText()), '/reject_proposal') === 0) {
    $proposalIdToReject = substr($updates->getMessage()->getText(), strlen('/reject_proposal'));
    $rejectedProposal = ArsysResearch::find($proposalIdToReject);

    if ($rejectedProposal) {
        $rejectedProposal->milestone_id = '28'; 
        $rejectedProposal->save();

        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "The proposal with ID {$proposalIdToReject} was successfully rejected and the milestone was changed!",
        ]);
    } else {
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Proposal with ID {$proposalIdToReject} not found!",
        ]);
    }
}


        //DEFENSE
        if (strtolower($updates->getMessage()->getText()) === '/predefense') {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "/request_predefense to check request of thesis defense\n/add_score to entry score of defense ",
                
            ]);
        }
        if (strtolower($updates->getMessage()->getText()) === '/request_predefense') {
            $pesan = "Here's the list of predefenses submitted:\n\n";
            foreach ($defenses as $sidang) {
                $pesan .= "/approve_predefense{$sidang->id}\n/reject_predefense{$sidang->id}\n(ID:{$sidang->id}) - {$sidang->title}\n\n";
            }
            if ($defenses->isEmpty()) {
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "No predefense were submitted.",
                ]);
            }
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $pesan,
            ]);
        }
        
if (strpos(strtolower($updates->getMessage()->getText()), '/approve_predefense') === 0) {
    $sidangIdToApprove = substr($updates->getMessage()->getText(), strlen('/approve_predefense'));
    $approvedPredefense = ArsysResearch::find($sidangIdToApprove);

    if ($approvedPredefense) {
        $approvedPredefense->milestone_id = '6'; 
        $approvedPredefense->save();

        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Predefense with ID {$sidangIdToApprove} successfully approved and milestone changed!",
        ]);
    } else {
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Predefense with ID {$sidangIdToApprove} not found!",
        ]);
    }
}

if (strpos(strtolower($updates->getMessage()->getText()), '/reject_predefense') === 0) {
    $sidangIdToReject = substr($updates->getMessage()->getText(), strlen('/reject_predefense'));
    $rejectedPredefense = ArsysResearch::find($sidangIdToReject);

    if ($rejectedPredefense) {
        $rejectedPredefense->milestone_id = '29'; 
        $rejectedPredefense->save();

        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Predefense with ID {$sidangIdToReject} successfully rejected and milestone changed!",
        ]);
    } else {
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Predefense with ID {$sidangIdToReject} not found!",
        ]);
    }
}
        // if (strtolower($updates->getMessage()->getText()) === '/presence') {
        //     return Telegram::sendMessage([
        //         'chat_id' => $chat_id,
        //         'text' => "feature will be soon",
        //     ]);
        // }
        // if (strtolower($updates->getMessage()->getText()) === '/score') {
        //     return Telegram::sendMessage([
        //         'chat_id' => $chat_id,
        //         'text' => "feature will be soon",
        //     ]);
        // }
        
        //YUDISIUM
        if (strtolower($updates->getMessage()->getText()) === '/yudisium') {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "/request_yudisium to approve or reject request of thesis's yudisium",
            ]);
        }
        if (strtolower($updates->getMessage()->getText()) === '/request_yudisium') {
            $reply = "Here's the list of yudisium submitted:\n\n";
            foreach ($yudisiums as $yudisium) {
                $reply .= "/approve_yudisium{$yudisium->id}\n(ID:{$yudisium->id}) - {$yudisium->title}\n\n";
            }
            if ($yudisiums->isEmpty()) {
                // Jika tidak ada proposal
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "No yudisium were submitted.",
                ]);
            }
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
            ]);
        }
        if (strtolower($updates->getMessage()->getText()) === '/approve_yudisium') {
            $reply = "Please select the yudisium you want to approve:\n\n";
            foreach ($yudisiums as $yudisium) {
                $reply .= "/approve_yudisium{$yudisium->id} - {$yudisium->title}\n\n";
            }
            if ($yudisiums->isEmpty()) {
                // Jika tidak ada proposal
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "No yudisium were submitted.",
                ]);
            }
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
            ]);
        }

        
if (strpos(strtolower($updates->getMessage()->getText()), '/approve_yudisium') === 0) {
    // Mendapatkan id proposal yang akan diapprove dari pesan (misalnya, /select_approve123)
    $yudisiumIdToApprove = substr($updates->getMessage()->getText(), strlen('/approve_yudisium'));

    // Melakukan perubahan di database (misalnya, mengubah milestone_id proposal)
    $approvedYudisium = ArsysResearch::find($yudisiumIdToApprove);

    if ($approvedYudisium) {
        $approvedYudisium->milestone_id = '12'; 
        $approvedYudisium->save();

        // Memberikan balasan ke pengguna
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Yudisium with ID {$yudisiumIdToApprove} successfully approved and milestone changed!",
        ]);
    } else {
        // Proposal tidak ditemukan
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Yudisium with ID {$yudisiumIdToApprove} not found!",
        ]);
    }
}


if (strtolower($updates->getMessage()->getText()) === '/reject_yudisium') {
    $reply = "Please select the yudisium you want to reject:\n\n";
    foreach ($yudisiums as $yudisium) {
        $reply .= "/reject_yudisium{$yudisium->id} - {$yudisium->title}\n\n";
    }
    if ($yudisiums->isEmpty()) {
        // Jika tidak ada proposal
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "No yudisium were submitted.",
        ]);
    }
    return Telegram::sendMessage([
        'chat_id' => $chat_id,
        'text' => $reply,
    ]);
}


if (strpos(strtolower($updates->getMessage()->getText()), '/reject_yudisium') === 0) {
    // Mendapatkan id proposal yang akan ditolak dari pesan (misalnya, /reject_proposal123)
    $yudisiumIdToReject = substr($updates->getMessage()->getText(), strlen('/reject_yudisium'));

    // Melakukan perubahan di database (misalnya, mengubah milestone_id proposal)
    $rejectedYudisium = ArsysResearch::find($yudisiumIdToReject);

    if ($rejectedYudisium) {
        $rejectedYudisium->milestone_id = '30'; 
        $rejectedYudisium->save();

        // Memberikan balasan ke pengguna
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Yudisium with ID {$yudisiumIdToReject} successfully rejected and milestone changed!",
        ]);
    } else {
        // Proposal tidak ditemukan
        return Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Yudisium with ID {$yudisiumIdToReject} not found!",
        ]);
    }
}
        
    
    // Check if the message is '/add_score'
if (strtolower($updates->getMessage()->getText()) === '/add_score') {
    // Save the chat ID for later use
    $chat_id = $updates->getChat()->getId();

    // Ask for research ID
    return Telegram::sendMessage([
        'chat_id' => $chat_id,
        'text' => "Please enter the examiner ID:",
    ]);
}

// Check if the message starts with '/research_id'
if (strpos(strtolower($updates->getMessage()->getText()), '/examiner_id') === 0) {
    // Extract the research ID from the message
    $examinerId = substr($updates->getMessage()->getText(), strlen('/examiner_id'));

    // Save the research ID for later use
    // (You might want to store it in a session or somewhere else depending on your application)
    // For example, you can use Laravel session
    session(['examiner_id' => $examinerId]);

    // Ask for the score
    return Telegram::sendMessage([
        'chat_id' => $chat_id,
        'text' => "Please enter the score:",
    ]);
}

// Check if the message starts with '/score'
if (strpos(strtolower($updates->getMessage()->getText()), '/score') === 0) {
    // Extract the score from the message
    $score = substr($updates->getMessage()->getText(), strlen('/score'));

    ArsysDefenseExaminerPresence::create([
        'event_id' => 12,
        'defense_examiner_id' => 1,
        'score' => $score
        
    ]);
       
    // Save the score for later use
    // (You might want to store it in a session or somewhere else depending on your application)
    // For example, you can use Laravel session
    session(['score' => $score]);
   
    // Ask for the remark
    return Telegram::sendMessage([
        'chat_id' => $chat_id,
        'text' => "Please enter the remark:",
    ]);
}

// Check if the message starts with '/remark'
if (strpos(strtolower($updates->getMessage()->getText()), '/remark') === 0) {
    // Extract the remark from the message
    $remark = substr($updates->getMessage()->getText(), strlen('/remark'));

    // Save the score and remark for later use
    // (You might want to store them in a session or somewhere else depending on your application)
    // session(['score' => $score]);  // Assuming $score is defined somewhere in your code
    session(['remark' => $remark]);

    // Create a new instance of the model and save it to the database
    // ArsysDefenseExaminerPresence::create([
    //     // 'score' => session('score'),
    //     'remark' => $remark,
        // Add any other necessary fields here
    // ]);

    // Now you have all the required information, and you can proceed to create the entry in the database
    // ...

    // Clear the session data
    // session()->forget(['research_id', 'score', 'remark']);

    // Send a success message
    return Telegram::sendMessage([
        'chat_id' => $chat_id,
        'text' => "New score with remark successfully added!",
    ]);
}


    

}

// Fungsi fiktif untuk mendapatkan discussant_id, sesuaikan sesuai dengan kebutuhan aplikasi Anda
private function getDiscussantId()
{
// ... (implementasi sesuai kebutuhan)
}

        
    }
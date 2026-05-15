<?php
namespace App\Repositories;

use App\interfaces\AppsheetRepositoryInterface;
use App\Models\Digital\WaMsgTmp;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppsheetRepository implements AppsheetRepositoryInterface
{
    public function create(array $data)
    {
        DB::beginTransaction();
        
        try {
            $chatId = $data['Chat ID 1'] ?? null;
            $task = $data['Task'] ?? null;
            $dueDate = $data['Due Date'] ?? null;
            $dueGapDays = $data['Due Gap (Days)'] ?? null;
            $description = $data['Description'] ?? '-';
            $assignedPersons = $data['Assigned Persons'] ?? null;
            $notes = $data['Notes'] ?? '-';
            
            // if (!$chatId) {
            //     throw new Exception(' Chat ID wajib diisi');
            // }

            // Format message
            $message = 
"Reminder Tugas - {$task}

Kepada Yth. {$assignedPersons},

Ini adalah pengingat mengenai tugas yang telah melewati batas waktu:
- Task: {$task}
- Due Date: {$dueDate}
- Due Gap: " . ($dueGapDays ?? 0) . " hari
- Deskripsi: {$description}

Notes: {$notes}

Mohon segera dilakukan tindak lanjut tugas tersebut.
Terima kasih atas perhatian dan kerjasamanya.

Salam";

            $wamsgtmp = WaMsgTmp::create([
                'no_hp' => $chatId,
                'kode_dealer' => 'C10',
                'module' => null,
                'jenis_msg' => 'Text',
                'message' => $message,
                'is_proses' => true,
                'status' => '9',
                'keterangan' => 'Reminder Task',
            ]);

            Log::info('Appsheet data saved successfully', [
                'id' => $wamsgtmp->id,
                'no_hp' => $chatId,
                'task' => $task
            ]);
            
            DB::commit();
            
            return $wamsgtmp;
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error in AppsheetRepository create: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e; 
        }
    }
}
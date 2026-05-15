<?php
namespace App\Http\Controllers\appsheet;

use App\Helper\ResponseHelper;
use App\Http\Requests\AppsheetStoreRequest;
use App\Http\Resources\AppsheetResource;
use App\interfaces\AppsheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Digital\WaMsgTmp;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AppsheetController extends Controller
{
    private AppsheetRepositoryInterface $appsheetRepository;

    public function __construct(AppsheetRepositoryInterface $appsheetRepository)
    {
        $this->appsheetRepository = $appsheetRepository;
    }
 
    public function store(AppsheetStoreRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $results = [];
            foreach ($validated['Appsheet'] as $appsheetData) {
                $appsheet = $this->appsheetRepository->create($appsheetData);
                $results[] = $appsheet;
            }
            
            return ResponseHelper::jsonResponse(
                true, 
                'Data Appsheet Berhasil Ditambahkan', 
                AppsheetResource::collection($results), 
                201
            );
        } catch (Exception $e) {
            Log::error('Error in AppsheetController store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return ResponseHelper::jsonResponse(
                false, 
                'Gagal menyimpan data: ' . $e->getMessage(), 
                null, 
                500
            );
        }
    }

    public function getTask()
    {
        try {
            DB::beginTransaction();
            
            // Query data dengan filter keterangan = 'Reminder Task' dan process_time = null
            $tasks = WaMsgTmp::where('keterangan', 'Reminder Task')
                ->whereNull('process_time')
                ->get(['id', 'no_hp', 'message']);
            
            // Update process_time untuk semua task yang diambil
            if ($tasks->isNotEmpty()) {
                $taskIds = $tasks->pluck('id')->toArray();
                
                $updatedCount = WaMsgTmp::whereIn('id', $taskIds)
                    ->update(['process_time' => now()]);
                
            }
            
            DB::commit();
            
            // Format response sesuai struktur yang diminta
            return response()->json([
                'Appsheet' => $tasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'no_hp' => $task->no_hp,
                        'message' => $task->message,
                    ];
                })
            ], 200);
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error in AppsheetController getTask: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function StoreTask()
    {
        try {
          
            $response = Http::timeout(30)->get('https://lmsdev.menara-agung.com/api/appsheet/tasks');
            
           
            if (!$response->successful()) {
                Log::error('Failed to fetch tasks from API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return ResponseHelper::jsonResponse(
                    false,
                    'Gagal mengambil data dari API',
                    null,
                    500
                );
            }
            
            $data = $response->json();
            
            if (!isset($data['Appsheet']) || !is_array($data['Appsheet'])) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Format data tidak valid',
                    null,
                    400
                );
            }
            
            $savedCount = 0;
            $errors = [];
            
         
            foreach ($data['Appsheet'] as $task) {
                try {
                
                    if (!isset($task['no_hp']) || !isset($task['message'])) {
                        $errors[] = "Data tidak lengkap untuk ID: " . ($task['id'] ?? 'unknown');
                        continue;
                    }
                    
                    $wamsgtmp = WaMsgTmp::create([
                        'no_hp' => $task['no_hp'],
                        'kode_dealer' => 'C10',
                        'module' => null,
                        'jenis_msg' => 'Text',
                        'message' => $task['message'],
                        'is_proses' => true,
                        'status' => '9',
                        'keterangan' => 'Reminder Task'
                    ]);
                    
                    $savedCount++;
                    
                    Log::info('Task saved successfully', [
                        'id' => $wamsgtmp->id,
                        'no_hp' => $task['no_hp']
                    ]);
                    
                } catch (Exception $e) {
                    $errors[] = "Gagal menyimpan task ID {$task['id']}: " . $e->getMessage();
                    Log::error('Error saving individual task', [
                        'task_id' => $task['id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $message = "Berhasil menyimpan {$savedCount} task";
            if (count($errors) > 0) {
                $message .= ". Gagal: " . count($errors) . " task";
            }
            
            return ResponseHelper::jsonResponse(
                true,
                $message,
                [
                    'saved_count' => $savedCount,
                    'failed_count' => count($errors),
                    'errors' => $errors
                ],
                200
            );
            
        } catch (Exception $e) {
            Log::error('Error in AppsheetController StoreTask: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return ResponseHelper::jsonResponse(
                false,
                'Gagal memproses data: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
}
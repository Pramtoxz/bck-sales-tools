<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Http\Requests\StoreProspekRequest;
use App\Http\Requests\UpdateProspekRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProspekController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $perPage = $request->query('per_page', 15);
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        $status = $request->query('status');

        $query = DB::connection('pgsql_nms')
            ->table('H1_DOS.guestbook')
            ->select(
                'guestbook.IDGuestBook',
                'guestbook.Tanggal',
                'guestbook.NamaCustomer',
                'guestbook.NoHp',
                'guestbook.KodeType',
                'guestbook.KodeWarna',
                'guestbook.DeskripsiWarnaMotor',
                'setupjenispembayaran.JenisPembayaran as rencana_pembayaran',
                'SetupTipeCustomer.tipe_customer',
                'guestbook.AlamatProspect',
                'guestbook.AlamatKantorProspect',
                'master_source_leads.deskripsi as source',
                'guestbook.Keterangan',
                'guestbook.Status_guestbook',
                'guestbook.created_at'
            )
            ->leftJoin('H1_DOS.setupjenispembayaran', 'setupjenispembayaran.IDJenisPembayaran', '=', 'guestbook.RencanaPembayaran')
            ->leftJoin('Master_Schema.SetupTipeCustomer', 'SetupTipeCustomer.id_tipe', '=', 'guestbook.TipeCustomer')
            ->leftJoin('Master_Schema.master_source_leads', 'master_source_leads.id', '=', 'guestbook.Source')
            ->where('guestbook.id_flp', $flp->id_flp)
            ->orderBy('guestbook.Tanggal', 'desc');

        if ($bulan) {
            $query->whereRaw('EXTRACT(MONTH FROM "Tanggal") = ?', [$bulan]);
        }

        if ($tahun) {
            $query->whereRaw('EXTRACT(YEAR FROM "Tanggal") = ?', [$tahun]);
        }

        if ($status) {
            $query->where('guestbook.Status_guestbook', $status);
        }

        $prospek = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $prospek->items(),
            'meta' => [
                'current_page' => $prospek->currentPage(),
                'last_page' => $prospek->lastPage(),
                'per_page' => $prospek->perPage(),
                'total' => $prospek->total(),
            ],
        ]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $prospek = DB::connection('pgsql_nms')
            ->table('H1_DOS.guestbook')
            ->select(
                'guestbook.IDGuestBook',
                'guestbook.Tanggal',
                'guestbook.NamaCustomer',
                'guestbook.NoHp',
                'guestbook.KodeType',
                'guestbook.KodeWarna',
                'guestbook.DeskripsiWarnaMotor',
                'setupjenispembayaran.JenisPembayaran as rencana_pembayaran',
                'SetupTipeCustomer.tipe_customer',
                'guestbook.AlamatProspect',
                'guestbook.AlamatKantorProspect',
                'master_source_leads.deskripsi as source',
                'guestbook.Keterangan',
                'guestbook.Status_guestbook',
                'guestbook.created_at',
                'guestbook.updated_at'
            )
            ->leftJoin('H1_DOS.setupjenispembayaran', 'setupjenispembayaran.IDJenisPembayaran', '=', 'guestbook.RencanaPembayaran')
            ->leftJoin('Master_Schema.SetupTipeCustomer', 'SetupTipeCustomer.id_tipe', '=', 'guestbook.TipeCustomer')
            ->leftJoin('Master_Schema.master_source_leads', 'master_source_leads.id', '=', 'guestbook.Source')
            ->where('guestbook.IDGuestBook', $id)
            ->where('guestbook.id_flp', $flp->id_flp)
            ->first();

        if (!$prospek) {
            return response()->json([
                'success' => false,
                'message' => 'Prospek tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prospek,
        ]);
    }

    public function store(StoreProspekRequest $request): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $validated = $request->validated();

        $year = date('y');
        $month = date('m');
        $dealer = $flp->kode_dealer;

        $lastId = DB::connection('pgsql_nms')
            ->table('H1_DOS.guestbook')
            ->where('IDGuestBook', 'like', "C10/{$dealer}/{$year}/{$month}/%")
            ->orderBy('IDGuestBook', 'desc')
            ->value('IDGuestBook');

        if ($lastId) {
            $parts = explode('/', $lastId);
            $lastNumber = (int)end($parts);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $idGuestBook = "C10/{$dealer}/{$year}/{$month}/PSP/{$validated['source']}/{$newNumber}";

        $prospek = GuestBook::create([
            'IDGuestBook' => $idGuestBook,
            'Tanggal' => $validated['tanggal'],
            'NamaCustomer' => $validated['nama_customer'],
            'NoHp' => $validated['no_hp'],
            'KodeType' => $validated['kode_type'],
            'KodeWarna' => $validated['kode_warna'],
            'RencanaPembayaran' => $validated['rencana_pembayaran'],
            'TipeCustomer' => $validated['tipe_customer'],
            'AlamatProspect' => $validated['alamat_prospect'],
            'AlamatKantorProspect' => $validated['alamat_kantor_prospect'] ?? null,
            'Source' => $validated['source'],
            'Keterangan' => $validated['keterangan'] ?? null,
            'id_flp' => $flp->id_flp,
            'NamaKariawan' => $flp->nama,
            'fk_dealer' => $flp->kode_dealer,
            'Status_guestbook' => 'f',
            'created_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prospek berhasil dibuat',
            'data' => [
                'id' => $prospek->IDGuestBook,
            ],
        ], 201);
    }

    public function update(UpdateProspekRequest $request, string $id): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $prospek = GuestBook::where('IDGuestBook', $id)
            ->where('id_flp', $flp->id_flp)
            ->first();

        if (!$prospek) {
            return response()->json([
                'success' => false,
                'message' => 'Prospek tidak ditemukan',
            ], 404);
        }

        if ($prospek->Status_guestbook === 't') {
            return response()->json([
                'success' => false,
                'message' => 'Prospek yang sudah approved tidak dapat diubah',
            ], 403);
        }

        $validated = $request->validated();

        $updateData = [];
        if (isset($validated['tanggal'])) $updateData['Tanggal'] = $validated['tanggal'];
        if (isset($validated['nama_customer'])) $updateData['NamaCustomer'] = $validated['nama_customer'];
        if (isset($validated['no_hp'])) $updateData['NoHp'] = $validated['no_hp'];
        if (isset($validated['kode_type'])) $updateData['KodeType'] = $validated['kode_type'];
        if (isset($validated['kode_warna'])) $updateData['KodeWarna'] = $validated['kode_warna'];
        if (isset($validated['rencana_pembayaran'])) $updateData['RencanaPembayaran'] = $validated['rencana_pembayaran'];
        if (isset($validated['tipe_customer'])) $updateData['TipeCustomer'] = $validated['tipe_customer'];
        if (isset($validated['alamat_prospect'])) $updateData['AlamatProspect'] = $validated['alamat_prospect'];
        if (isset($validated['alamat_kantor_prospect'])) $updateData['AlamatKantorProspect'] = $validated['alamat_kantor_prospect'];
        if (isset($validated['source'])) $updateData['Source'] = $validated['source'];
        if (isset($validated['keterangan'])) $updateData['Keterangan'] = $validated['keterangan'];

        $prospek->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Prospek berhasil diupdate',
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $flp = $user->flp;

        if (!$flp) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai FLP',
            ], 403);
        }

        $prospek = GuestBook::where('IDGuestBook', $id)
            ->where('id_flp', $flp->id_flp)
            ->first();

        if (!$prospek) {
            return response()->json([
                'success' => false,
                'message' => 'Prospek tidak ditemukan',
            ], 404);
        }

        if ($prospek->Status_guestbook === 't') {
            return response()->json([
                'success' => false,
                'message' => 'Prospek yang sudah approved tidak dapat dihapus',
            ], 403);
        }

        $prospek->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prospek berhasil dihapus',
        ]);
    }
}

# Sales Tools Mobile App - Project Roadmap

# Aturan Kerja
Wajib standart industri dan selalu tanya context7
Tanpa Komentar // di codingan
wajib mengikuti pola yang sudah ada

## Project Overview
Migrasi dari Telegram Bot ke Mobile App API untuk sales FLP (Field Sales Person).
Database: `dms_clone` (PostgreSQL) - shared database, no migrations allowed.

## Database Setup ✅ COMPLETED
- [x] Table `personal_access_tokens` created (Laravel Sanctum)
- [x] Table `flp` modified (added: user_id, last_login, is_active)
- [x] Table `flp_devices` created (multi-device tracking)
- [x] Data sync: flp.user_id linked to users.id via kd_kariawan

## Authentication System ✅ COMPLETED
### Relasi
- `users.kd_kariawan` = `flp.id_flp`
- Multi-device support (flexible login)
- **Custom token authentication (No Sanctum - PHP 7.1+ compatible)**

### Endpoints
- [x] `POST /api/auth/login` - Login with email & password ✅ TESTED
- [x] `POST /api/auth/logout` - Logout current device
- [x] `POST /api/auth/logout-all` - Logout all devices
- [x] `GET /api/auth/devices` - List active devices
- [x] `GET /api/auth/me` - Get current user info

### Models
- [x] `app/Models/User.php` - Custom token methods
- [x] `app/Models/Flp.php` - FLP model with relations
- [x] `app/Models/FlpDevice.php` - Device tracking model
- [x] `app/Models/PersonalAccessToken.php` - Custom token storage

### Controllers
- [x] `app/Http/Controllers/Api/AuthController.php`

### Resources
- [x] `app/Http/Resources/UserResource.php`
- [x] `app/Http/Resources/FlpResource.php`

### Requests
- [x] `app/Http/Requests/LoginRequest.php`

### Providers
- [x] `app/Providers/AuthServiceProvider.php` - Custom auth driver

### Configuration
- [x] Guard `auth:api` with `custom-token` driver
- [x] Sanctum removed (lighter & PHP 7.1+ compatible)

## Dashboard API ✅ COMPLETED
- [x] `GET /api/dashboard` - Dashboard data ✅ TESTED
- [x] DashboardService created
- [x] Pencapaian hari ini calculation
- [x] Summary metrics (SPK, Indent)

## Feature APIs (From Telegram Bot)

### 1. Target Sales ✅ COMPLETED
- [x] `GET /api/target-sales` - Get target vs actual sales per series ✅ TESTED
- [x] TargetFlp model created
- [x] Logic reused from telegram bot
- [x] Filter: exclude target = 0

### 2. Stock Unit ✅ COMPLETED
- [x] `GET /api/stock` - Get stock by search (kode item, tipe, warna) ✅ TESTED
- [x] Query from: `H1_DOS.stokunit` + `H1_DOS.mastergroupsegmenmotor` + `public.tblwarna`
- [x] Search: kode item, tipe motor, warna
- [x] Response: include kode_warna, warna, jumlah

### 3. List Indent ✅ COMPLETED
- [x] `GET /api/indent` - Get active indent list ✅ TESTED
- [x] Query from: `H1_DOS.indent` + joins
- [x] Logic reused from telegram bot
- [x] Grouped by tipe motor
- [x] Response: customer_id, customer_name, leasing, kode_item, warna

## New Features (From UI Prototype)

### 4. Target Prospek ✅ COMPLETED
- [x] `GET /api/target-prospek` - Target vs actual prospek ✅ TESTED
- [x] Table: `H1_DOS.tbl_target_prospek` + count from `guestbook`
- [x] Response: bulan, tahun, target, actual, selisih, persentase, tercapai

### 5. Actual Prospek ✅ COMPLETED
- [x] `GET /api/prospek` - List prospek with pagination ✅ TESTED
- [x] Table: `H1_DOS.guestbook` + joins (setupjenispembayaran, SetupTipeCustomer, master_source_leads)
- [x] Query params: per_page, bulan, tahun, status
- [x] Response: joined data with descriptions (not codes)

### 6. Input Prospek (CRUD) ✅ COMPLETED
- [x] `POST /api/prospek` - Create prospek ✅ TESTED
- [x] `PUT /api/prospek/{id}` - Update prospek ✅ TESTED
- [x] `DELETE /api/prospek/{id}` - Delete prospek ✅ TESTED
- [x] Validation: StoreProspekRequest, UpdateProspekRequest (with JSON error response)
- [x] Auto generate IDGuestBook format: C10/{dealer}/{yy}/{mm}/PSP/{source}/{number}
- [x] Security: only owner can edit/delete, cannot edit/delete approved prospek

## Error Handling ✅ COMPLETED
- [x] All Form Requests return JSON response on validation failure (422)
- [x] Login error returns JSON with proper message (401)
- [x] Consistent error format across all endpoints

### 7. Actual SPK ✅ COMPLETED
- [x] `GET /api/actual-spk` - Actual SPK data ✅ TESTED
- [x] Table: `H1_DOS.spk` + joins (mastercustomer, SpkDetail, setupjenispembayaran)
- [x] Query params: per_page, bulan, tahun, status
- [x] Response: SPK details with customer, motor, payment info

### 8. Performance FLP ✅ COMPLETED
- [x] `GET /api/performance` - FLP performance ranking/leaderboard ✅ TESTED
- [x] Ranking based on: persentase pencapaian target sales
- [x] Response: my_rank + leaderboard (top N)
- [x] Query params: start_date, end_date, limit

### 9. Actual Sales ✅ COMPLETED
- [x] `GET /api/actual-sales` - Actual sales data ✅ TESTED
- [x] Table: `H1_DOS.fakturpenjualan` + joins (salesorder, spk, mastercustomer, SpkDetail, setupjenispembayaran)
- [x] Query params: per_page, bulan, tahun
- [x] Response: Sales details with customer, motor, payment info (Tdpp, Tppn, Tbbn, Tamount)
- [x] Postman collection updated with 3 examples

### 10. Profile Management ✅ COMPLETED
- [x] `GET /api/profile` - Get profile ✅ TESTED
- [x] `PUT /api/profile` - Update profile (name, email, no_hp, password)
- [x] `POST /api/profile/photo` - Upload photo to public/photos/flp/

## Technical Standards
- **No comments in code** - Self-documenting code only
- **Use Context7** for Laravel best practices
- **Industry standard** - Follow Laravel conventions
- **API Resources** - Use Laravel API Resources for responses
- **Validation** - Use Form Requests for validation
- **Error Handling** - Consistent error response format
- **Pagination** - Implement for list endpoints
- **Rate Limiting** - Apply to sensitive endpoints

## Response Format Standard
```json
{
  "success": true,
  "message": "Success message",
  "data": {},
  "meta": {
    "pagination": {}
  }
}
```

## Error Response Format
```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

## Next Steps (Priority Order)
1. ✅ Setup custom authentication (No Sanctum)
2. ✅ Create User & Flp models with relations
3. ✅ Build AuthController (login, logout, me)
4. ✅ Test authentication flow with Postman
5. **→ Build Dashboard API** (NEXT)
6. Migrate existing features from Telegram Bot
7. Build new features based on UI prototype
8. Add rate limiting & security measures
9. API documentation (Postman collection or OpenAPI)

## Questions to Resolve
- [x] Pencapaian hari ini 80% - calculated from target vs actual sales (DashboardService)
- [x] Target Prospek & Actual Prospek - `H1_DOS.tbl_target_prospek` + count from `guestbook`
- [x] Performance FLP - ranking based on persentase pencapaian target sales
- [x] Input Prospek - use existing `H1_DOS.guestbook` table
- [x] Dealer info - from `H1_DOS.masterdealer` (already implemented in Dashboard)

## Progress Summary

**✅ ALL FEATURES COMPLETED:**
1. Authentication System (Custom token, PHP 7.1+ compatible)
2. Dashboard API (Pencapaian, Summary metrics)
3. Target Sales API (Target vs actual per series)
4. Stock Unit API (Search by kode, tipe, warna)
5. List Indent API (Grouped by tipe motor)
6. Target Prospek API (Target vs actual prospek)
7. Actual Prospek API (List with pagination & filters)
8. Input Prospek CRUD (Create, Update, Delete with validation)
9. Actual SPK API (List with pagination & filters)
10. Performance FLP API (Ranking/leaderboard based on target achievement)
11. Actual Sales API (Sales data from fakturpenjualan)
12. Profile Management API (View, update, photo upload)
13. Error Handling (JSON response for all validation errors)
14. Postman Collection (organized with folders, all endpoints included)

**📊 Database Changes:**
- Using `H1_DOS.tblflp` (no_id = id_flp) instead of `public.flp`
- Target from `H1_DOS.tbl_target_flp`
- Custom authentication without Sanctum
- Photo storage in `public/photos/flp/`

**📝 API Endpoints Summary:**
- Authentication: 5 endpoints (login, logout, logout-all, me, devices)
- Dashboard: 1 endpoint
- Sales: 2 endpoints (target-sales, actual-sales)
- Stock: 1 endpoint (search)
- Indent: 1 endpoint (list)
- Prospek: 5 endpoints (target, list, create, update, delete)
- SPK: 1 endpoint (actual-spk with filters)
- Performance: 1 endpoint (ranking/leaderboard)
- Profile: 3 endpoints (get, update, upload photo)
- Health Check: 1 endpoint
- **Total: 21 endpoints completed**

**🎯 Optional Future Enhancements:**
1. Rate limiting for sensitive endpoints
2. API documentation (Swagger/OpenAPI)
3. Caching for frequently accessed data
4. Push notifications integration
5. Export data to Excel/PDF

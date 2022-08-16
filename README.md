# Rapidev Test Submission
Repo untuk hasil pengerjaan test dari Rapidev.
## Requirement
 - Web Server
 - Database MySQL >= 8
 - PHP >= 7

## Dokumentasi
### AUTH & CRUD Promo

 - Autentikasi
    - Untuk melakukan login bisa mengakses `{host}/auth/login`
    - Untuk logout, akses `{host}/auth/logout`
    
 - Halaman Promo
    - Untuk melihat halaman index (data promo), akses `{host}/promotion`
    - Untuk menambah data promo, akses `{host}/promotion/create`
    - Untuk mengubah data promo, akses `{host}/promotion/edit/:id_promo`
    - Untuk menghapus data promo, akses `{host}/promotion/delete/:id_promo`

### Endpoint

 - Memperoleh room rates
	 
     Untuk memperoleh room rates bisa dengan mengakses url dengan method:
     
	 `GET` : `{host}/property/:property_id/rooms?check_in=:param1&check_out=:param2`
    
    > :property_id adalah id properties/hotel
    
    > :param1 adalah tanggal check in (format yyyy-mm-dd)
    
    > :param2 adalah tanggal check out (format yyyy-mm-dd)


 - Memperoleh harga total dan total diskon reservasi
	 
     Untuk memperoleh harga total dan total diskon reservasi bisa dengan mengakses url dengan method:
     
	 `GET` : `{host}/reservation/:room_id?check_in=:param1&check_out=:param2&amount=:param3`
    
    > :room_id adalah id room/kamar
    
    > :param1 adalah tanggal check in (format yyyy-mm-dd)
    
    > :param2 adalah tanggal check out (format yyyy-mm-dd)
    
    > :param3 adalah total

  

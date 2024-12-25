

INSERT INTO tb_karyawan (ID_Karyawan, Nama_Karyawan, Tanggal_Lahir, Alamat, Telepon, ID_Jabatan, ID_Divisi, ID_Golongan)
VALUES ('K001', 'John Doe', '1990-01-01', 'Jl. ABC No. 123', '081234567890', 'J001', 'D001', 'G001');


SELECT * FROM tb_karyawan;


UPDATE tb_karyawan
SET Nama_Karyawan = 'Jane Doe'
WHERE ID_Karyawan = 'K001';


DELETE FROM tb_karyawan WHERE ID_Karyawan = 'K001';


SELECT k.ID_Karyawan, k.Nama_Karyawan, d.Nama_Divisi
FROM tb_karyawan k
INNER JOIN tb_divisi d ON k.ID_Divisi = d.ID_Divisi;



/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     11/15/2025 7:47:36 AM                        */
/*==============================================================*/


alter table PEMINJAMAN 
   drop foreign key FK_PEMINJAM_RELATIONS_BUKU;

alter table PEMINJAMAN 
   drop foreign key FK_PEMINJAM_RELATIONS_USERS;

drop table if exists BUKU;


alter table PEMINJAMAN 
   drop foreign key FK_PEMINJAM_RELATIONS_BUKU;

alter table PEMINJAMAN 
   drop foreign key FK_PEMINJAM_RELATIONS_USERS;

drop table if exists PEMINJAMAN;

drop table if exists USERS;

/*==============================================================*/
/* Table: BUKU                                                  */
/*==============================================================*/
create table BUKU
(
   ID_BUKU              int not null  comment '',
   JUDUL                varchar(200)  comment '',
   PENULIS              varchar(100)  comment '',
   PENERBIT             varchar(100)  comment '',
   TAHUN_TERBIT         date  comment '',
   COVER                varchar(255)  comment '',
   KATEGORI             varchar(1)  comment '',
   primary key (ID_BUKU)
);

/*==============================================================*/
/* Table: PEMINJAMAN                                            */
/*==============================================================*/
create table PEMINJAMAN
(
   ID_PEMINJAMAN        int not null  comment '',
   ID_BUKU              int  comment '',
   ID_USER              int  comment '',
   TANGGAL_PEMINJAMAN   date  comment '',
   TANGGAL_KEMBALI      date  comment '',
   STATATUS             varchar(1)  comment '',
   primary key (ID_PEMINJAMAN)
);

/*==============================================================*/
/* Table: USERS                                                 */
/*==============================================================*/
create table USERS
(
   ID_USER              int not null  comment '',
   NAMA_USER            varchar(100)  comment '',
   EMAIL                varchar(200)  comment '',
   NIM_NIP              varchar(200)  comment '',
   PASSWORD             varchar(255)  comment '',
   ROLE                 varchar(1)  comment '',
   PROFIL               varchar(200)  comment '',
   primary key (ID_USER)
);

alter table PEMINJAMAN add constraint FK_PEMINJAM_RELATIONS_BUKU foreign key (ID_BUKU)
      references BUKU (ID_BUKU) on delete restrict on update restrict;

alter table PEMINJAMAN add constraint FK_PEMINJAM_RELATIONS_USERS foreign key (ID_USER)
      references USERS (ID_USER) on delete restrict on update restrict;


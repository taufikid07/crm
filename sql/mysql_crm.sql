-- ----------------------------
-- Table structure for crm_history
-- ----------------------------
CREATE TABLE `crm_history` (
  `id_history` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(50) DEFAULT NULL,
  `new_date` date DEFAULT NULL,
  `new_time` time DEFAULT NULL,
  `komentar` text,
  `id_quote` int(50) DEFAULT NULL,
  `id_invoice` int(50) DEFAULT NULL,
  `kode_history` int(50) DEFAULT NULL,
  PRIMARY KEY (`id_history`)
) ENGINE=InnoDB AUTO_INCREMENT=384 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_invoice
-- ----------------------------
CREATE TABLE `crm_invoice` (
  `id_invoice` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_quote` int(10) NOT NULL,
  `id_perusahaan` int(10) NOT NULL,
  `id_kegiatan` int(10) DEFAULT NULL,
  `id_kontak` int(10) NOT NULL,
  `project_manager` int(10) NOT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `deskripsi` text,
  `tgl_TerbitInvoice` date DEFAULT NULL,
  `terbit_invoice` varchar(100) DEFAULT NULL,
  `bahasa` varchar(100) DEFAULT NULL,
  `syarat` text,
  `tgl_TerbitKuitansi` date DEFAULT NULL,
  `terbit_kuitansi` varchar(100) DEFAULT NULL,
  `sub_total` varchar(250) DEFAULT NULL,
  `tax_percent` varchar(250) DEFAULT NULL,
  `tax` varchar(250) DEFAULT NULL,
  `total_max` varchar(250) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  `kopstat` int(5) DEFAULT NULL,
  `ttdstat` int(5) DEFAULT NULL,
  `codeq` int(50) DEFAULT NULL,
  `send_client` int(50) DEFAULT NULL,
  PRIMARY KEY (`id_invoice`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_kegiatan
-- ----------------------------
CREATE TABLE `crm_kegiatan` (
  `id_kegiatan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_perusahaan` int(10) NOT NULL,
  `nama_kegiatan` text,
  PRIMARY KEY (`id_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_komentar
-- ----------------------------
CREATE TABLE `crm_komentar` (
  `id_komentar` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_quote` int(50) DEFAULT NULL,
  `uid` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `komentar` text,
  `emailto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_komentar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_komentarin
-- ----------------------------
CREATE TABLE `crm_komentarin` (
  `id_komentar` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_invoice` int(50) DEFAULT NULL,
  `uid` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `komentar` text,
  `emailto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_komentar`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_kontak
-- ----------------------------
CREATE TABLE `crm_kontak` (
  `id_kontak` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_perusahaan` int(50) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_kontak`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_listinvoice
-- ----------------------------
CREATE TABLE `crm_listinvoice` (
  `id_product` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_invoice` int(10) DEFAULT NULL,
  `description` text,
  `quantity` varchar(50) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `order` varchar(50) DEFAULT NULL,
  `unit_price` varchar(250) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `orderby` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB AUTO_INCREMENT=998 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_listproduct
-- ----------------------------
CREATE TABLE `crm_listproduct` (
  `id_product` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_quote` int(10) DEFAULT NULL,
  `description` text,
  `quantity` varchar(50) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `order` varchar(50) DEFAULT NULL,
  `unit_price` varchar(250) DEFAULT NULL,
  `total` varchar(100) DEFAULT NULL,
  `orderby` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB AUTO_INCREMENT=5852 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_listunit
-- ----------------------------
CREATE TABLE `crm_listunit` (
  `id_listunit` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `nama_unit` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_listunit`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_perusahaan
-- ----------------------------
CREATE TABLE `crm_perusahaan` (
  `id_perusahaan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(250) NOT NULL,
  `kode_perusahaan` varchar(100) DEFAULT NULL,
  `alamat` text,
  `kontak_perusahaan` varchar(200) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  PRIMARY KEY (`id_perusahaan`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_quotation
-- ----------------------------
CREATE TABLE `crm_quotation` (
  `id_quote` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_perusahaan` int(10) NOT NULL,
  `id_kontak` int(10) NOT NULL,
  `project_manager` int(10) NOT NULL,
  `no_quote` varchar(50) DEFAULT NULL,
  `id_kegiatan` int(10) DEFAULT NULL,
  `deskripsi` text,
  `tgl_TerbitInvoice` date DEFAULT NULL,
  `terbit_invoice` varchar(100) DEFAULT NULL,
  `bahasa` varchar(100) DEFAULT NULL,
  `syarat` text,
  `tgl_TerbitKuitansi` date DEFAULT NULL,
  `terbit_kuitansi` varchar(100) DEFAULT NULL,
  `sub_total` varchar(250) DEFAULT NULL,
  `tax_percent` varchar(250) DEFAULT NULL,
  `tax` varchar(250) DEFAULT NULL,
  `total_max` varchar(250) DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  `kopstat` int(5) DEFAULT NULL,
  `ttdstat` int(5) DEFAULT NULL,
  `codeq` int(5) DEFAULT NULL,
  `send_client` int(50) DEFAULT NULL,
  PRIMARY KEY (`id_quote`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_rekening
-- ----------------------------
CREATE TABLE `crm_rekening` (
  `id_rekening` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_quote` int(50) DEFAULT NULL,
  `nama_rek` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_rekening`)
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_reply
-- ----------------------------
CREATE TABLE `crm_reply` (
  `id_reply` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_komentar` int(50) DEFAULT NULL,
  `uid` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `reply` text,
  `rmail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_reply`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for crm_replyin
-- ----------------------------
CREATE TABLE `crm_replyin` (
  `id_reply` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `id_komentar` int(50) DEFAULT NULL,
  `uid` int(5) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `reply` text,
  `rmail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_reply`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

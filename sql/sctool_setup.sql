--�����e�[�u���̍폜
DROP table blog, inquiry_file, inquiry_chat, inquiry, pre_register, user_info, new_info, application, redmine_apply, kurorom_apply;

--���⍇���`���b�g�p�̃e�[�u���쐬
CREATE TABLE inquiry_chat(
  message_no    VARCHAR(100),
  inquiry_no    VARCHAR(100),
  user_no       INT,
  message       VARCHAR(6000),
  send_time     datetime,
);

--���⍇���p�e�[�u���쐬
CREATE TABLE inquiry(
  inquiry_no  VARCHAR(100),
  user_no     INT,
  subject     VARCHAR(60),
  body        VARCHAR(6000),
  create_time datetime,
  status      VARCHAR(9),
  publishing_setting    VARCHAR(9),
  deleted_flg TINYINT default 0,
  cc          VARCHAR(100)
);

--���⍇���Y�t�t�@�C���p�e�[�u���쐬
CREATE TABLE inquiry_file(
  file_no      INT NOT NULL IDENTITY(1,1) PRIMARY KEY,
  inquiry_no   VARCHAR(100),
  file_name    VARCHAR(300),
  file_path    VARCHAR(900),
  deleted_flg  TINYINT default 0
);

--���o�^�p�̃e�[�u���쐬
CREATE TABLE pre_register (
  pre_register_no   INT NOT NULL IDENTITY(1,1) PRIMARY KEY,
  token             VARCHAR(128),
  mail              VARCHAR(90),
  date              datetime,
  invalid_flg       TINYINT default 0
);

--���[�U�[���p�̃e�[�u���쐬
CREATE TABLE user_info(
  user_no     INT NOT NULL IDENTITY(1,1) PRIMARY KEY,
  name        VARCHAR(30),
  mail        VARCHAR(90),
  password    VARCHAR(12),
  admin_flg   TINYINT,
  deleted_flg TINYINT default 0
);

--user_info�ɏ����Ǘ��҃A�J�E���g��}��
INSERT INTO user_info (name, mail, password, admin_flg) VALUES ('�Ǘ���', 'system@sample.com', 'aaaaaa', '1');

--�V�����p�e�[�u���쐬
CREATE TABLE new_info(
  newinfo_no  INT NOT NULL IDENTITY(1,1) PRIMARY KEY,
  major_category    VARCHAR(60),
  subject     VARCHAR(150),
  body        VARCHAR(3000),
  post_time   datetime
);

--�u���O�p�e�[�u���쐬
CREATE TABLE blog(
  blog_no    INT NOT NULL IDENTITY(1,1) PRIMARY KEY,
  major_category    VARCHAR(60),
  subject     VARCHAR(150),
  body        VARCHAR(3000),
  image       VARCHAR(300),
  post_time   datetime
);

--�\���Ǘ��e�[�u���쐬
CREATE TABLE application(
  apply_id       VARCHAR(100),
  type           VARCHAR(30),
  department     VARCHAR(60),
  name           VARCHAR(30),
  mail           VARCHAR(90),
  apply_date     datetime,
  delivery_date  datetime,
  reseption_department  VARCHAR(60),
  reseption_date datetime,
  complete_date  datetime,
  status      VARCHAR(20) default '���Ή�'
);

--redmine�\���p�e�[�u���쐬
CREATE TABLE redmine_apply(
  apply_id      VARCHAR(100),
  category      VARCHAR(30),
  family_name   VARCHAR(30),
  first_name    VARCHAR(60),
  id            VARCHAR(90),
  note          VARCHAR(900)
);

--��ROM�}�X�^�o�^�\���p�e�[�u���쐬
CREATE TABLE kurorom_apply(
  apply_id      VARCHAR(100),
  category      VARCHAR(30),
  site          VARCHAR(15),
  item_number   VARCHAR(60),
  later_site    VARCHAR(60),
  new_item_number   VARCHAR(60),
  item_name     VARCHAR(60)
);
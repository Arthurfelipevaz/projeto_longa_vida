create database longavida;


create table plano
 (
  pla_numero int primary key not null,
  pla_descricao varchar(30),
  pla_valor decimal(10,2)
  );
  
create table associado
 (
 cli_plano int not null,
 cli_nome char(40) primary key not null,
 cli_Endereco char(35),
 cli_cidade char(20),
 cli_estado char(2),
 cli_cep char (9),
 FOREIGN KEY (cli_plano) REFERENCES plano(pla_numero)
  ); 
  
<?php

    // classe dashboard
    class Dashboard {

        public $data_inicio;
        public $data_fim;
        public $numeroVendas;
        public $totalVendas;
        public $clientesAtivos;
        public $clientesinativos;
        public $totalReclamacao;
        public $totalElogios;
        public $totalSugestoes;
        public $totaldespesas;  
                

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
            return $this;
        }

    }

    //classe de conexao com banco

    class Conexao {
        
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar() {
            
            try {

                $conexao = new PDO (

                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );
                    
                //
                $conexao->exec('set charset utf8');

                return $conexao;

            } catch (PDOException $e) {
                echo '<p> '.$e->getMessage(). '</p>';

            }
        }
    }

    // classe (model)

    Class Bd {
        private $conexao;
        private $dashboard;


        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
            
        }
        //////////////////////////////////////////////////////////////////////////////////////

        public function getNumeroVendas() {

            $query = 
                'select 
                    count(*) as numero_vendas 
                from 
                    tb_vendas 
                WHERE 
                    data_venda 
                BETWEEN ? AND ?';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(1, $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(2, $this->dashboard->__get('data_fim'));
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }
        //////////////////////////////////////////////////////////////////////////////////////

        public function getTotalVendas() {

            $query = 
                'select
                    SUM(total) as total_vendas 
                from 
                    tb_vendas
                WHERE 
                    data_venda 
                BETWEEN ? AND ?';
        
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(1, $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(2, $this->dashboard->__get('data_fim'));
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

        //////////////////////////////////////////////////////////////////////////////////////        

        public function getClientesAtivos() {

            $query = 
                'select 
                    COUNT(*) 
                FROM 
                    tb_clientes as clientes_ativos
                WHERE 
                    cliente_ativo';
        
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(1, $this->dashboard->__get('clientesAtivos'));
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
            
             
        }

        //////////////////////////////////////////////////////////////////////////////////////


    }


//logica do script
$dashboard = new Dashboard();
$conexao = new Conexao();


//gerar dinamicamente a data para capturar o enento competencia
$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0]; 
$mes = $competencia[1];
$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio', $ano. '-' .$mes. '-' .'-01');
$dashboard->__set('data_fim', $ano. '-' .$mes. '-' .$dias_do_mes);


$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());





 echo json_encode($dashboard); //transfere a string para obj











?>
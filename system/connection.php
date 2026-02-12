<?php
class MySQL
{
	private mysqli $conexion;
	private $total_consultas;

	public function __construct()
	{
		//http://www.php.net/manual/es/mysqlinfo.api.choosing.php
		$ini_array = parse_ini_file('bd.ini');

		if (!isset($this->conexion)) {
			$this->conexion = new mysqli($ini_array["IP"], $ini_array["USR"], $ini_array["PWD"], $ini_array["DB"], $ini_array["PORT"]);
			//$this->conexion = new mysqli('$ini_array["IP"]','livreWeb_dba','kB2h$w^%8exH','livreWeb_db','3306');
		}
	}

	/** @var mysqli $conexion */
	public function getConexion(): mysqli
	{
		return $this->conexion;
	}


	public function consulta($consulta)
	{
		$this->total_consultas++;
		$resultado = $this->conexion->query($consulta);
		if (!$resultado) {
			echo mysqli_error($this->conexion);
			echo "<br>" . $consulta;
			exit;
		}
		return $resultado;
	}

	public function execute($sql, $params = [])
	{
		$stmt = $this->conexion->prepare($sql);

		if (!$stmt) {
			die("Error en prepare: " . $this->conexion->error);
		}

		if (!empty($params)) {

			// Detectar tipos automáticamente
			$types = '';
			foreach ($params as $param) {
				if (is_int($param)) {
					$types .= 'i';
				} elseif (is_double($param)) {
					$types .= 'd';
				} else {
					$types .= 's';
				}
			}

			$stmt->bind_param($types, ...$params);
		}

		if (!$stmt->execute()) {
			die("Error en execute: " . $stmt->error);
		}

		return true;
	}


	public function fetch_array($consulta)
	{
		return mysqli_fetch_array($consulta);
	}

	public function fetch_assoc($consulta)
	{
		return mysqli_fetch_assoc($consulta);
	}

	public function num_rows($consulta)
	{
		return mysqli_num_rows($consulta);
	}

	public function getTotalConsultas()
	{
		return $this->total_consultas;
	}

	public function getLastId()
	{
		return mysqli_insert_id($this->conexion);
	}

	// Método nuevo para evitar inyección SQL
	public function escape_string($valor)
	{
		return $this->conexion->real_escape_string($valor);
	}
}

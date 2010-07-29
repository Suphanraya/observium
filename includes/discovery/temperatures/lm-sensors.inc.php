<?php

global $valid_sensor;

if ($device['os'] == "linux")
{
  $oids = snmp_walk($device, "lmTempSensorsDevice", "-Osqn", "LM-SENSORS-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  if ($oids) echo("LM-SENSORS ");
  foreach(explode("\n", $oids) as $data)
  {
    $data = trim($data);
    if ($data)
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $temperature_id = $split_oid[count($split_oid)-1];
      $temperature_oid  = "1.3.6.1.4.1.2021.13.16.2.1.3.$temperature_id";
      $temperature = snmp_get($device, $temperature_oid, "-Ovq") / 1000;
      $descr = str_ireplace("temperature-", "", $descr);
      $descr = trim($descr);
      if($temperature != "0" && $temperature <= "1000")
      {
        discover_sensor($valid_sensor, 'temperature', $device, $temperature_oid, $temperature_id, 'lmsensors', $descr, '1000', '1', NULL, NULL, NULL, NULL, $temperature);
      }
    }
  }
}

?>

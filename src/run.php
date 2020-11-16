<?php
$run1_cmd = 'php run-delete_old_data.php';

$run2_cmd = 'php run-minifier_user.php';

$run3_cmd = 'php run-minifier_client.php';

$run4_cmd = 'php run-dump_master_data.php';


echo "Starting step 1...\n";
echo shell_exec ($run1_cmd);
echo "Done step 1\n";

echo "Starting step 2...\n";
echo shell_exec($run2_cmd);
echo "Done step 2\n";

echo "Starting step 3...\n";
echo shell_exec($run3_cmd);
echo "Done step 3\n";

echo "Starting step 4...\n";
echo shell_exec($run4_cmd);
echo "Done step 4\n";

echo "Done dump!\n";
<?php

$exclude = ['Registry', 'Backend'];
$result = [];
foreach (glob("src/*") as $path) {
    $module = basename($path);
    if (in_array($module, $exclude)) {
        continue;
    }
    $result[$module] = [];
    $uses = shell_exec("grep -rh '^use App' " .  escapeshellarg($path)); // find `use App`
    if (!$uses) {
        continue;
    }
    $uses = explode("\n", $uses);
    $uses = array_filter($uses); // remove empty
    $uses = array_map(fn ($use) => substr($use, strlen("use App\\")), $uses); // remove `use App\`
    $uses = array_map(fn ($use) => explode('\\', $use)[0], $uses); // resolve module name
    $uses = array_unique($uses);
    $uses = array_filter($uses, fn ($use) => $use != $module); // reject itself
    $uses = array_filter($uses, fn ($use) => !in_array($use, $exclude)); // exclude
    sort($uses);
    $result[$module] = $uses;
}

if (isset($argv[1]) && $argv[1] == 'source') {
    echo "digraph A {\n";
    foreach ($result as $module => $uses) {
        echo "    $module;\n";
        foreach ($uses as $use) {
            echo "    $module -> $use;\n";
        }
    }
    echo "}n";
}
else if (isset($argv[1]) && $argv[1] == 'online') {
    $out = "digraph A {\n";
    foreach ($result as $module => $uses) {
        $out .= "    $module;\n";
        foreach ($uses as $use) {
            $out .= "    $module -> $use;\n";
        }
    }
    $out .= "}\n";
    echo "https://dreampuf.github.io/GraphvizOnline/#" . rawurlencode($out) . "\n";
} else {
    $out = "";
    foreach ($result as $module => $uses) {
        $out .= "$module\n";
        foreach ($uses as $use) {
            $out .= " $use\n";
        }
    }
    file_put_contents('tools/mrd/mrd.dump', $out);
    echo "Dumped to tools/mrd/mrd.dump\n";
}

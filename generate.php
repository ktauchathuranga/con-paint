<?php
// generate.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datesJson = $_POST['dates'] ?? '[]';
    $year = $_POST['year'] ?? date('Y');
    $commitsPerDay = intval($_POST['commits'] ?? 1);
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $timezone = $_POST['timezone'] ?? 'UTC';
    $dates = json_decode($datesJson, true);

    if (!is_array($dates) || empty($dates)) {
        die('No dates selected.');
    }
    if (empty($username) || empty($email)) {
        die('Username and email are required.');
    }

    // Sanitize inputs for safe use in shell commands
    $username = escapeshellarg($username);
    $email = escapeshellarg($email);

    // Set the timezone for date handling
    if (!date_default_timezone_set($timezone)) {
        date_default_timezone_set('UTC'); // Fallback to UTC if invalid timezone
    }

    // Sort dates chronologically
    sort($dates);

    // Create temporary directory for the repo
    $tempDir = sys_get_temp_dir() . '/gitrepo_' . uniqid();
    mkdir($tempDir);

    // Initialize git repo
    chdir($tempDir);
    exec('git init --initial-branch=main');
    exec("git config user.name $username");
    exec("git config user.email $email");

    // Create empty commits for selected dates
    foreach ($dates as $date) {
        $timestamp = strtotime($date . ' 00:00:00');
        $formattedDate = date('D M j H:i:s Y O', $timestamp);

        for ($i = 0; $i < $commitsPerDay; $i++) {
            // Slightly offset time to avoid identical timestamps, but stay in same day
            $offsetTimestamp = $timestamp + ($i * 60); // 1 minute apart
            $offsetFormatted = date('D M j H:i:s Y O', $offsetTimestamp);
            exec("git commit --allow-empty -m 'Commit on $date' --date='$offsetFormatted' 2>&1");
        }
    }

    // Create zip file
    $zipFile = sys_get_temp_dir() . '/repo.zip';
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die('Failed to create zip file.');
    }

    // Add repo to zip
    addDirToZip($zip, $tempDir, 'repo/');
    $zip->close();

    // Send zip for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="github_contribution_repo.zip"');
    header('Content-Length: ' . filesize($zipFile));
    readfile($zipFile);

    // Cleanup
    unlink($zipFile);
    rrmdir($tempDir);

    exit;
}

function addDirToZip($zip, $dir, $root = '')
{
    $handle = opendir($dir);
    while (false !== $f = readdir($handle)) {
        if ($f != '.' && $f != '..') {
            $path = $dir . '/' . $f;
            $localPath = $root . $f;
            if (is_file($path)) {
                $zip->addFile($path, $localPath);
            } elseif (is_dir($path)) {
                $zip->addEmptyDir($localPath);
                addDirToZip($zip, $path, $localPath . '/');
            }
        }
    }
    closedir($handle);
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                $path = $dir . '/' . $object;
                if (is_dir($path)) {
                    rrmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
        rmdir($dir);
    }
}

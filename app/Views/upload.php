<!DOCTYPE html>
<html>
<head>
    <title>Upload Music to Playlist</title>
</head>
<body>
    <h1>Upload Music to Playlist</h1>
    
    <form id="uploadForm" enctype="multipart/form-data" method="post">
        <input type="file" name="musicFile" id="musicFile" accept=".mp3,.wav">
        <button type="button" id="uploadButton">Upload</button>
    </form>

    <div id="uploadResult"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadForm = document.getElementById('uploadForm');
            const uploadButton = document.getElementById('uploadButton');
            const uploadResult = document.getElementById('uploadResult');

            uploadButton.addEventListener('click', function () {
                const formData = new FormData(uploadForm);

                fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        uploadResult.innerHTML = 'File uploaded successfully!';
                    } else {
                        uploadResult.innerHTML = 'File upload failed: ' + data.message;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    uploadResult.innerHTML = 'An error occurred during file upload.';
                });
            });
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['musicFile']['name']);
        
        $response = [];

        if (move_uploaded_file($_FILES['musicFile']['tmp_name'], $uploadFile)) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'File upload failed.';
        }

        echo '<script>var responseData = ' . json_encode($response) . ';</script>';
    }
    ?>
</body>
</html>
<!DOCTYPE html>
<html lang="<?= $this->Locale->get('lang') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $this->Locale->get($title) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/docs"><?= $this->Locale->get('Documentation') ?></a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1><?= $this->Locale->get('404 - Documentation Not Found') ?></h1>
                <p><?= $this->Locale->get($message) ?></p>
                <a href="/docs" class="btn btn-primary"><?= $this->Locale->get('Back to Documentation') ?></a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
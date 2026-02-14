<?php
require_once 'includes/config.php';

logoutUser();
redirect('index.php', 'Vous avez été déconnecté avec succès.', 'info');
<?php
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Nouveau hash : " . $hash;
?>
```

3. **Enregistre** ce fichier comme `generer_password.php` dans `C:\xampp\htdocs\my_bredexpress\`

4. **Dans ton navigateur**, va sur :
```
http://localhost/my_bredexpress/generer_password.php
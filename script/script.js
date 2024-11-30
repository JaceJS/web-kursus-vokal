document.addEventListener('DOMContentLoaded', function () {
    const loginButton = document.getElementById('login-btn');
    const registerButton = document.getElementById('register-btn');
    const daftarKursusButton = document.getElementById('daftar-kursus-btn');

    // Submit form login
    if (loginButton) {
        loginButton.addEventListener('click', function (event) {
            event.preventDefault();
            const email = document.getElementById('email-login').value;
            const password = document.getElementById('password-login').value;

            if (!email || !password) {
                alert('Email dan password harus diisi!');
                return;
            }

            const formData = new FormData();
            formData.append('account_option', 'login');
            formData.append('email-login', email);
            formData.append('password-login', password);

            fetch('pendaftaran.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.status === 'success') {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        alert("Terjadi kesalahan saat memproses respons.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan, silakan coba lagi.");
                });
        });
    }

    // Submit form registrasi
    if (registerButton) {
        registerButton.addEventListener('click', function (event) {
            event.preventDefault();
            const name = document.getElementById('name-signup').value;
            const email = document.getElementById('email-signup').value;
            const password = document.getElementById('password-signup').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const phone = document.getElementById('phone-signup').value;

            if (!name || !email || !password || !confirmPassword || !phone) {
                alert('Semua kolom harus diisi!');
                return;
            }

            if (password !== confirmPassword) {
                alert('Password dan konfirmasi password tidak cocok!');
                return;
            }

            const formData = new FormData();
            formData.append('account_option', 'register');
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('confirm_password', confirmPassword);
            formData.append('phone', phone);

            fetch('pendaftaran.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(text => {
                    try {
                        console.log('Response:', text);
                        const data = JSON.parse(text);
                        if (data.status === 'success') {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        alert("Terjadi kesalahan saat memproses respons.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan, silakan coba lagi.");
                });
        });
    }

    // Submit form pendaftaran kursus
    if (daftarKursusButton) {
        daftarKursusButton.addEventListener('click', function (event) {
            event.preventDefault();
            // Tambahkan validasi dan pengiriman form untuk pendaftaran kursus di sini
        });
    }
});

function toggleAccountForm() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (document.getElementById('login').checked) {
        loginForm.classList.add('show');
        registerForm.classList.remove('show');
    } else {
        registerForm.classList.add('show');
        loginForm.classList.remove('show');
    }
}

import './bootstrap';
import lottie from 'lottie-web';

document.addEventListener('DOMContentLoaded', function() {
    const lottieContainer = document.getElementById('lottie-login');
    if (lottieContainer) {
        lottie.loadAnimation({
            container: lottieContainer,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets2.lottiefiles.com/packages/lf20_jcikwtux.json' // Example login animation
        });
    }
});

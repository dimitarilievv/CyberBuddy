<div
    x-data="{
        time: @entangle('timeLeft'),
        started: false
    }"
    x-init="
        if (!started) {
            started = true;
            const interval = setInterval(() => {
                if (time > 0) {
                    time--;
                } else {
                    clearInterval(interval);
                    $wire.dispatch('quiz-timer-ended');
                }
            }, 1000);
        }
    "
>
    <span x-text="Math.floor(time / 60) + ':' + String(time % 60).padStart(2, '0')"></span>
</div>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

@isset($title)
    <title>{{ $title }} | ราชมงคลสกลนคร </title>
@else
    <title>ราชมงคลสกลนคร </title>
@endisset

<link rel="preconnect" href="https://www.skc.rmuti.ac.th">
<link href="https://www.skc.rmuti.ac.th/kanit/css2.css?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet" />

@vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
@stack('styles')
    <script>
        window.setAppearance = function(appearance) {
            let setDark = () => document.documentElement.classList.add('dark')
            let setLight = () => document.documentElement.classList.remove('dark')
            let setButtons = (appearance) => {
                document.querySelectorAll('button[onclick^="setAppearance"]').forEach((button) => {
                    button.setAttribute('aria-pressed', String(appearance === button.value))
                })
            }
            if (appearance === 'system') {
                let media = window.matchMedia('(prefers-color-scheme: dark)')
                window.localStorage.removeItem('appearance')
                media.matches ? setDark() : setLight()
            } else if (appearance === 'dark') {
                window.localStorage.setItem('appearance', 'dark')
                setDark()
            } else if (appearance === 'light') {
                window.localStorage.setItem('appearance', 'light')
                setLight()
            }
            if (document.readyState === 'complete') {
                setButtons(appearance)
            } else {
                document.addEventListener("DOMContentLoaded", () => setButtons(appearance))
            }
        }
        window.setAppearance(window.localStorage.getItem('appearance') || 'system')
    </script>

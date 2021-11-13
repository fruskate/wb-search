function makeMagia()
{
    const dots = document.getElementsByClassName('dot');

    for (let i = 0; i < dots.length; i++){
        w = Math.round(Math.random()*300+100);
        dots[i].style.width = String(w)+'px';
        dots[i].style.height = dots[i].style.width;

        let color = Math.round(Math.random()*2 + 1);

        if (color == 1) {dots[i].style.backgroundColor = '#721e66';} else
        if (color == 2) {dots[i].style.backgroundColor = '#ec1480';} else
        if (color == 3) {dots[i].style.backgroundColor = '#842076';}

        x = Math.random()- 0.5;
        let left;
        if (x > 0){
            left = Math.round(window.screen.width/2 + window.screen.width*0.30 - x*(window.screen.width*0.20) - w/2);
        } else {
            left = Math.round(window.screen.width/2 - window.screen.width*0.30 + x*(window.screen.width*0.20) - w/2);
        }

        dots[i].style.left = String(left)+'px';

        dots[i].style.top = String(Math.round(Math.random()*window.innerHeight- w + 100))+'px';

    }

    const blank = document.getElementById('predict');


    const balls = document.getElementsByClassName('ball');



    function DotsAnimation() {
        requestAnimationFrame(DotsAnimation);
        draw();
    }
    requestAnimationFrame(DotsAnimation);

    function draw(){
        let time = new Date().getTime() * 0.002;
        let x = Math.sin( time ) / 4;
        let y = Math.cos( time * 0.8 )/ 4;

        for (let i = 0; i < dots.length; i++){
            let str = dots[i].style.left;
            let left = Number(str.substr(0,str.length - 2));
            str = dots[i].style.top;
            let top = Number(str.substr(0,str.length - 2));

            dots[i].style.left = String(left+ x)+'px';
            dots[i].style.top = String(top + y)+'px';
        }
    }
}

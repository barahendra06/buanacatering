<style type="text/css">
	.play-button {
		width: 90px;
		height: 60px;
		background-color: #060606;
		box-shadow: 0 0 30px rgba( 0,0,0,0.6 );
		z-index: 1;
		opacity: 0.8;
		border-radius: 6px;
	}
	.play-button:before {
		content: "";
		border-style: solid;
		border-width: 15px 0 15px 26.0px;
		border-color: transparent transparent transparent #fff;
	}
	.play-button {
		cursor: pointer;
	}
	.play-button,
	.play-button:before {
		position: absolute;
	}
	.play-button,
	.play-button:before {
		top: 50%;
		left: 50%;
		transform: translate3d( -50%, -50%, 0 );
	}

	.home-youtube-thumb
	{
	    position:absolute;
	    width: 100%;
	    top:0;
	    left:0;
	    right:0;
	    bottom:0;
	    margin:auto;
	}
	.youtube-button-wrapper
	{
		z-index: 2;
	    position:absolute;
	    top:0;
	    left:0;
	    right:0;
	    bottom:0;
	    background:rgba(119, 119, 119, 0.3);
	    cursor: pointer;
	}
</style>

<script>
	var youtubeEl;
	var youtubeWrapper = document.getElementsByClassName("youtube-button-wrapper");
	Array.from(youtubeWrapper).forEach(function(element) {
      element.onclick = getYoutube;
    });


    function getYoutube(e) 
    {
    	var el = e.target;
		var tag = document.getElementById('youtubeScript');

		var iframe = document.createElement( "iframe" );

		iframe.setAttribute( "frameborder", "0" );
		iframe.setAttribute( "allowfullscreen", "" );
		iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ el.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );

		el.innerHTML = "";
		el.appendChild( iframe );
	}



</script>
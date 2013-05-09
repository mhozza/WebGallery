new Sonic({
			
	width: 160,
	height: 100,

	stepsPerFrame: 1,    // best between 1 and 5
	trailLength: 0.5,    // between 0 and 1
	pointDistance: 0.07, // best between 0.01 and 0.05
	fps: 16,

	backgroundColor: 'transparent',
	//fillColor: '#EFd933',
    fillColor: '#ccc',

	path: [
		['arc', 50, 50, 30, 0, 360],
        ['arc', 110, 50, 30, 180, -180],        
	],

	step: function(point, index, frame) {

		// Here you can do custom stuff.
		// `this._` is a HTML 2d Canvas Context

		var sizeMultiplier = 7; // try changing this :)
		
		// Add frame number in lower-left corner
		this._.fillText(0|frame*this.fps, 1, 99);
		
		this._.beginPath();
		//this._.moveTo(point.x, point.y);
		this._.arc(
			point.x, point.y,
			(index==1 ? 0.4 : .3) * sizeMultiplier, 0,
			Math.PI*2, false
		);
		this._.closePath();
		this._.fill();

	}

});
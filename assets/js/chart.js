$(document).ready(function () {

	// Bar Chart
	// Define colors for each column (college)
	const colorMap = {
		'CICTE': '#FF5733',    // Red-Orange
		'CBM': '#33FF57',      // Green
		'CONAHS': '#3357FF',   // Blue
		'FISHERIES': '#FF33A1', // Pink
		'CAS': '#FFAA33',      // Orange
		'CCJE': '#AA33FF',     // Purple
		'EDUCATION': '#33FFF5' // Cyan
	};

	Morris.Bar({
		element: 'bar-charts',
		data: [
			{ y: 'CICTE', a: 100 },
			{ y: 'CBM', a: 75 },
			{ y: 'CONAHS', a: 50 },
			{ y: 'FISHERIES', a: 75 },
			{ y: 'CAS', a: 50 },
			{ y: 'CCJE', a: 75 },
			{ y: 'EDUCATION', a: 100 }
		],
		xkey: 'y',
		ykeys: ['a'],
		labels: ['Employed'],

		// Corrected function to assign colors per column
		barColors: function (row) {
			return colorMap[row.label] || "#000000"; // Uses row.label, default to black if not found
		},

		resize: true,
		redraw: true
	});





	// Line Chart

	Morris.Line({
		element: 'line-charts',
		data: [
			{ y: '2006', a: 50, b: 90 },
			{ y: '2007', a: 75, b: 65 },
			{ y: '2008', a: 50, b: 40 },
			{ y: '2009', a: 75, b: 65 },
			{ y: '2010', a: 50, b: 40 },
			{ y: '2011', a: 75, b: 65 },
			{ y: '2012', a: 100, b: 50 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Total Sales', 'Total Revenue'],
		lineColors: ['#f43b48', '#453a94'],
		lineWidth: '3px',
		resize: true,
		redraw: true
	});

});
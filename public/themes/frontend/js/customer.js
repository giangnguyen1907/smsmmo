
	const pdfFile = atob(url);

	// Initialize PDF.js
	const pdfjsLib = window['pdfjs-dist/build/pdf'];
	
	// Specify workerSrc property to the worker file located within the pdf.js distribution
	pdfjsLib.GlobalWorkerOptions.workerSrc = "/themes/frontend/js/pdf.worker.min.js";
	
	// Load PDF document
	const loadingTask = pdfjsLib.getDocument(pdfFile);
	loadingTask.promise.then(pdf => {
		// Render PDF pages
		for (let pageNum = 1; pageNum <= limit; pageNum++) {
			pdf.getPage(pageNum).then(page => {
				const scale = 1.2;
				const viewport = page.getViewport({ scale });

				// Prepare canvas element
				const canvas = document.createElement('canvas');
				const context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page to canvas
				const renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				page.render(renderContext).promise.then(() => {
					document.getElementById('pdf-viewer').appendChild(canvas);
				});
			});
		}
	});
	
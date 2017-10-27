var Index = {

	suggestions : [ {
            "id": "name",
            "label": "name"
        },
		{
            "id": "address",
            "label": "address"
        },
		{
            "id": "signature",
            "label": "signature"
        },
		{
            "id": "company",
            "label": "company"
        },

    {
            "id": "categories",
            "label": "categories"
        },

        {
            "id": "email",
            "label": "email"
        },
        {
            "id": "phone",
            "label": "phone"
        },
    ],

	init : function()
	{
//		Index.bindEvents();
//		$('#getNewSuggestionsButton1').click();
		Index.initCkEditor();
	},

//	bindEvents : function()
//	{
//		$('#getNewSuggestionsButton1').on('click', function()
//		{
//			Index.getSuggestionsFromServer('data/suggestions1.json');
//		});

//		$('#getNewSuggestionsButton2').on('click', function()
//		{
//			Index.getSuggestionsFromServer('data/suggestions2.json');
//		});
//	},

	initCkEditor : function()
	{
		//Here "CKEDITOR.SHIFT + 51" is the key combination for '#'
		$('textarea#ckeditorBox').ckeditor({ suggestionsTriggerKey: { keyCode: CKEDITOR.SHIFT + 52 }});
		CKEDITOR.on( 'instanceReady', function( evt ) {
			//Here 'Index.suggestions' is the Array which is holding the current list of suggestions
			CKEDITOR.instances.ckeditorBox.execCommand('reloadSuggetionBox',Index.suggestions);
		});

		$('textarea.ckeditorBox').ckeditor({ suggestionsTriggerKey: { keyCode: CKEDITOR.SHIFT + 52 }});
		CKEDITOR.on( 'instanceReady', function( evt ) {
			//Here 'Index.suggestions' is the Array which is holding the current list of suggestions
			CKEDITOR.instances.ckeditorBox.execCommand('reloadSuggetionBox',Index.suggestions);
		});
	},

	getSuggestionsFromServer : function(url)
	{
		Index.suggestions = [ {
	            "id": "name",
	            "label": "name"
	        },
			{
	            "id": "address",
	            "label": "address"
	        },
			{
	            "id": "signature",
	            "label": "signature"
	        },
			{
	            "id": "company",
	            "label": "company"
	        },

	    {
	            "id": "categories",
	            "label": "categories"
	        },

	        {
	            "id": "email",
	            "label": "email"
	        },
	        {
	            "id": "phone",
	            "label": "phone"
	        },
    ],
		Index.ajaxCall(url,'',Index.getSuggestionsFromServerCallback);
	},

	getSuggestionsFromServerCallback : function(response)
	{

		var sugggestions = response.sugggestions;

		$.each(sugggestions, function(index,sugggestion)
		{
			Index.suggestions.push({
							"id" : sugggestion.id,
							"label" : sugggestion.label
						});
		});
		CKEDITOR.instances.ckeditorBox.execCommand('reloadSuggetionBox',Index.suggestions);
	},

//	ajaxCall : function(urlForAjax,dataForAjax,successCallBack)
//	{
//		$.ajax({
//					type : 'POST',
//					url : urlForAjax,
//					data : dataForAjax,
//					contentType: "application/json",
//					dataType : "json",
//					success : function(response) {
//						if (response !== null) {
//							successCallBack(response);
//						} else
//						{
//							alert("Unable to get a response from the server.");
//						}
//					},
//					error : function() {
//						alert("Experiencing problems connecting to the server.");
//					}
//				});
//	}


}

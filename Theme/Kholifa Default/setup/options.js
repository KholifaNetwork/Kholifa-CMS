var knDesignOptions = {
    textColor: function (value) {
        $('.knModuleForm .knmLabel, .knWidget-contentText td, .knWidget-Text').css('color', value);
        knDesign.reloadLessFiles(['theme']);
    },
    linkColor: function (value) {
        $('footer a, .knWidget-Text a, .knWidget-File a').css('color', value);
        knDesign.reloadLessFiles(['theme']);
    },
    bodyBackgroundColor: function (value) {
        'use strict';
        $('body').css('background-color', value);
    },
    backgroundColor: function (value) {
        'use strict';
        $('.wrapper').css('background-color', value);
    }
};

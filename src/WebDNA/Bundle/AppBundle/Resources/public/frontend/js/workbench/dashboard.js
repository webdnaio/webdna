var WebDNADashboard = {
    counters: [],
    getWebsites: function (website_id) {
        $.getJSON(Routing.generate('user_workbench_dashboard_data_xhr', {website: website_id}), function(context) {
            var source = $("#websites-list-template").html();
            var template = Handlebars.compile(source);
            WebDNADashboard.counters = context.counters;
            $('#websites-list').html(template(context));
        });
    },
    getAnalysisCounters: function(ap_id) {
        $.getJSON(Routing.generate('user_workbench_dashboard_ap_counter_xhr', {analysisProcess: ap_id}), function(context) {
            var source = $("#progress-template").html();
            var template = Handlebars.compile(source);
            WebDNADashboard.counters[ap_id] = context;
            $('#progress-block').html(template(context));
        });
    },
    initHandlebarsHelpers: function() {
        Handlebars.registerHelper('isAnalysisCompleted', function(conditional, options) {
            if(conditional == 4) {
                return options.fn(this);
            }
        });
        Handlebars.registerHelper('isAnalysisPreparing', function(conditional, options) {
            if(conditional == 1) {
                return options.fn(this);
            }
        });
        Handlebars.registerHelper('isAnalysisInProgress', function(conditional, options) {
            var ap_status = parseInt(conditional);
            if(ap_status === 2 || ap_status === 3 || ap_status === -11) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        });
        Handlebars.registerHelper('dateFromNow', function(context) {
            if (window.moment) {
                return moment(context).fromNow();
            } else {
                return context;
            }
        });
        Handlebars.registerHelper('getTotalCount', function(ap_id) {
            if (typeof WebDNADashboard.counters[ap_id] !== 'undefined') {
                return WebDNADashboard.counters[ap_id].count;
            }
        });
        Handlebars.registerHelper('getCountProcessed', function(ap_id) {
            if (typeof WebDNADashboard.counters[ap_id] !== 'undefined') {
                return WebDNADashboard.counters[ap_id].countProcessed;
            }
        });
        Handlebars.registerHelper('getCountProcessedPercent', function(ap_id) {
            if (typeof WebDNADashboard.counters[ap_id] !== 'undefined') {
                return WebDNADashboard.counters[ap_id].countProcessed*100/WebDNADashboard.counters[ap_id].count;
            }
        });
        Handlebars.registerHelper('isCounterInitialised', function(ap_id, options) {
            if(typeof WebDNADashboard.counters[ap_id] !== 'undefined' && WebDNADashboard.counters[ap_id].count > 0) {
                return options.fn(this);
            } else {
                return options.inverse(this);
            }
        });
        Handlebars.registerHelper('getAnalysisUrl', function(website_id, ap_id) {
            return Routing.generate('user_workbench_report_table', {website: website_id, analysisProcess: ap_id});
        });
        Handlebars.registerHelper('getAnalysisSelectSourceUrl', function(ap_id) {
            return Routing.generate('user_workbench_website_data', {analysisProcess: ap_id});
        });
        Handlebars.registerHelper('getUrlByAnalysisStatus', function(ap_status, website_id, ap_id) {
            var url;
            switch(ap_status) {
                case '1':
                    url = Routing.generate('user_workbench_website_data', {analysisProcess: ap_id});
                    break;
                case '2':
                case '3':
                case '4':
                    url = Routing.generate('user_workbench_report_table', {website: website_id, analysisProcess: ap_id});
                    break;
            }
            return url;
        });
    }
};

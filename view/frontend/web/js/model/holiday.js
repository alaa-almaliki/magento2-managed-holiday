define(
    ["jquery", "mage/url"], function ($, url) {
        return {

            /**
             * Gets a list of holidays between given from/to dates
             *
             * @param data
             * @param callback
             */
            between: function (callback, data) {
                this.ajax('rest/v1/holiday/between', data, callback);
            },

            /**
             * Gets a list of holidays starting from today's date
             *
             * @param data
             * @param callback
             */
            nextHolidays: function (callback, data) {
                this.ajax('rest//v1/holiday/next', data, callback);
            },

            /**
             * Checks whether current or given date is holiday
             *
             * @param data
             * @param callback
             */
            isHoliday: function (callback, data) {
                this.ajax('rest/v1/holiday/is_holiday', data, callback);
            },

            /**
             * Gets Holiday By Id
             *
             * @param id
             * @param callback
             */
            getHoliday: function (callback, id) {
                this.ajax('rest/v1/holiday/' + id, callback);
            },

            /**
             *
             * @param urlPath
             * @param data
             * @param callback
             */
            ajax: function (urlPath, data, callback) {
                let config = {
                    url: url.build(urlPath),
                    type: "GET"
                };

                if ($.isFunction(callback)) {
                    config['success'] = callback;
                }

                if (undefined !== data) {
                    config['data'] = data;
                }

                $.ajax(config);
            }
        }
    }
);
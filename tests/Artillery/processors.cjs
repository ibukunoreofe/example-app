// processors.cjs
module.exports = {
    logBookCount: function(requestParams, response, context, ee, next) {
        try {
            const books = JSON.parse(response.body);
            console.log(`Number of books fetched: ${books.length}`);
        } catch (error) {
            console.error('Error parsing response:', error);
        }
        return next();
    },

    logRequest: function(requestParams, context, ee, next) {
        console.log('Sending Request:');
        console.log('URL:', requestParams.url);
        console.log('Headers:', requestParams.headers);
        console.log('Body:', requestParams.body);
        return next();
    },

    logResponse: function(requestParams, response, context, ee, next) {
        console.log('Received Response:');
        console.log('Status Code:', response.statusCode);
        console.log('Headers:', response.headers);
        console.log('Body:', response.body);
        return next();
    }
};

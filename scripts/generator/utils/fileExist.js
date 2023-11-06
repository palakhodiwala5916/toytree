/**
 * componentExists
 *
 * Check whether the given component exist in either the components or containers directory
 */

const fs = require('fs');

function componentExists(filePath) {
    return fs.existsSync(filePath);
}

module.exports = componentExists;

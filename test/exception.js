class BadRequestException extends Error {
    constructor(message, code, status, debugData) {
        super(message); 
        this.name = "BadRequestException"; 
        this.status = status;
        this.code = code;
        this.debugData = debugData;
    }
}

exports.BadRequestException = BadRequestException;

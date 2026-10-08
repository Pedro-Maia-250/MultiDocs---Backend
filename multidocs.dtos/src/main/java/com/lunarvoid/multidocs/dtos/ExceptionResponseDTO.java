package com.lunarvoid.multidocs.dtos;

import java.time.Instant;

import com.fasterxml.jackson.annotation.JsonFormat;

public class ExceptionResponseDTO {
    
    @JsonFormat(shape = JsonFormat.Shape.STRING, pattern = "yyy-MM-dd'T'HH-mm-ss'Z'", timezone = "GMT")
    private final Instant timestamp;
    private final Integer status;
    private final String Error;
    private final String message;
    private final String path;

    public ExceptionResponseDTO(Integer status, String error, String message, String path) {
        this.timestamp = Instant.now();
        this.status = status;
        Error = error;
        this.message = message;
        this.path = path;
    }

    public Instant getTimestamp() {
        return timestamp;
    }
    public Integer getStatus() {
        return status;
    }
    public String getError() {
        return Error;
    }
    public String getMessage() {
        return message;
    }
    public String getPath() {
        return path;
    }

}

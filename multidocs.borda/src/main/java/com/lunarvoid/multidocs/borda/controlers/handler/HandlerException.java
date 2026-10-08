package com.lunarvoid.multidocs.borda.controlers.handler;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.AuthenticationException;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.client.RestClientResponseException;

import com.lunarvoid.multidocs.dtos.ExceptionResponseDTO;

import jakarta.servlet.http.HttpServletRequest;

@ControllerAdvice
public class HandlerException {

    @ExceptionHandler(RestClientResponseException.class)
    public ResponseEntity<ExceptionResponseDTO> handleRestClientException(RestClientResponseException ex, HttpServletRequest request) {
        // Tenta desserializar o JSON de erro que veio do outro serviço
        try {
            ExceptionResponseDTO downstreamError = ex.getResponseBodyAs(ExceptionResponseDTO.class);
            if (downstreamError != null) {
                return ResponseEntity.status(ex.getStatusCode()).body(downstreamError);
            }
        } catch (Exception e) {
            // Se a resposta de erro não for no formato ExceptionResponseDTO
        }

        // Se falhar o parse ou não houver corpo
        ExceptionResponseDTO response = new ExceptionResponseDTO(
                ex.getStatusCode().value(),
                "Erro de Comunicação",
                ex.getStatusText(),
                request.getRequestURI()
        );
        return ResponseEntity.status(ex.getStatusCode()).body(response);
    }
    
    @ExceptionHandler(RuntimeException.class)
    public ResponseEntity<ExceptionResponseDTO> databaseException(RuntimeException exception, HttpServletRequest request){
        HttpStatus status = HttpStatus.BAD_REQUEST;
        ExceptionResponseDTO response = new ExceptionResponseDTO(status.value(), "error nv1", exception.getMessage(), request.getRequestURI());
        return ResponseEntity.status(status).body(response);
    }

    @ExceptionHandler(AuthenticationException.class)
    public ResponseEntity<ExceptionResponseDTO> databaseException(AuthenticationException exception, HttpServletRequest request){
        HttpStatus status = HttpStatus.BAD_REQUEST;
        ExceptionResponseDTO response = new ExceptionResponseDTO(status.value(), "error nv2", exception.getMessage(), request.getRequestURI());
        return ResponseEntity.status(status).body(response);
    }

    @ExceptionHandler(Exception.class)
    public ResponseEntity<ExceptionResponseDTO> databaseException(Exception exception, HttpServletRequest request){
        HttpStatus status = HttpStatus.BAD_REQUEST;
        ExceptionResponseDTO response = new ExceptionResponseDTO(status.value(), "error nv3", exception.getMessage(), request.getRequestURI());
        return ResponseEntity.status(status).body(response);
    }
}

# Microservice API Documentation

## Overview
This document describes the RESTful API endpoints for the microservice architecture implemented in this Laravel application.

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
Most endpoints require API key authentication. Include the API key in the request header:
```
X-API-Key: your-api-key-here
```

Default test API keys:
- `test-api-key-123`
- `dev-api-key-456`

## Response Format
All API responses follow this standard format:

### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": ["Detailed error messages"]
}
```

### Paginated Response
```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": [...],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7
    }
}
```

## Endpoints

### Health Check
**GET** `/health`
- **Description**: Check API health status
- **Authentication**: None required
- **Response**: System status information

### Test Endpoints
**GET** `/test`
- **Description**: Basic test endpoint
- **Authentication**: Required
- **Response**: Test message and timestamp

**POST** `/test`
- **Description**: Test endpoint with data validation
- **Authentication**: Required
- **Body Parameters**:
  - `name` (required, string, max:255)
  - `description` (optional, string, max:1000)

**GET** `/test/system-info`
- **Description**: Get system information
- **Authentication**: Required
- **Response**: PHP version, Laravel version, memory usage

### Services
**GET** `/services`
- **Description**: List all services
- **Authentication**: Required
- **Query Parameters**:
  - `per_page` (optional, integer, default: 15)
  - `search` (optional, string)
- **Response**: Paginated list of services

**POST** `/services`
- **Description**: Create a new service
- **Authentication**: Required
- **Body Parameters**:
  - `name` (required, string, max:255)
  - `description` (optional, string)
  - `status` (optional, string, default: 'active')

**GET** `/services/{id}`
- **Description**: Get specific service details
- **Authentication**: Required
- **Response**: Service details

**PUT** `/services/{id}`
- **Description**: Update a service
- **Authentication**: Required
- **Body Parameters**: Same as POST

**DELETE** `/services/{id}`
- **Description**: Delete a service
- **Authentication**: Required

**GET** `/services/statistics`
- **Description**: Get service statistics
- **Authentication**: Required

### Subscriptions
**GET** `/subscriptions`
- **Description**: List all subscriptions
- **Authentication**: Required
- **Query Parameters**:
  - `per_page` (optional, integer, default: 15)
  - `status` (optional, string)
  - `subscriber_id` (optional, integer)
  - `service_offer_id` (optional, integer)

**POST** `/subscriptions`
- **Description**: Create a new subscription
- **Authentication**: Required
- **Body Parameters**:
  - `subscriber_id` (required, integer)
  - `service_offer_id` (required, integer)
  - `status` (optional, string, default: 'active')
  - `start_date` (optional, date)
  - `end_date` (optional, date)
  - `canal` (optional, string)

**GET** `/subscriptions/{id}`
- **Description**: Get specific subscription details
- **Authentication**: Required

**PUT** `/subscriptions/{id}`
- **Description**: Update a subscription
- **Authentication**: Required

**DELETE** `/subscriptions/{id}`
- **Description**: Delete a subscription
- **Authentication**: Required

**POST** `/subscriptions/{id}/activate`
- **Description**: Activate a subscription
- **Authentication**: Required

**POST** `/subscriptions/{id}/deactivate`
- **Description**: Deactivate a subscription
- **Authentication**: Required

### Transactions
**GET** `/transactions`
- **Description**: List all transactions
- **Authentication**: Required
- **Query Parameters**:
  - `per_page` (optional, integer, default: 15)
  - `subscription_id` (optional, integer)
  - `date_from` (optional, date)
  - `date_to` (optional, date)
  - `min_amount` (optional, numeric)
  - `max_amount` (optional, numeric)

**POST** `/transactions`
- **Description**: Create a new transaction
- **Authentication**: Required
- **Body Parameters**:
  - `subscription_id` (required, integer)
  - `price` (required, numeric, min: 0)
  - `description` (optional, string, max: 1000)

**GET** `/transactions/{id}`
- **Description**: Get specific transaction details
- **Authentication**: Required

**GET** `/transactions/subscriber/{subscriberId}`
- **Description**: Get transactions by subscriber
- **Authentication**: Required

**GET** `/transactions/statistics`
- **Description**: Get transaction statistics
- **Authentication**: Required

## Error Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized (Invalid or missing API key)
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting
- Default: 60 requests per minute
- Burst limit: 100 requests
- Rate limits can be configured in `config/api.php`

## Testing
Use the included `test_api.php` script to test the API endpoints:
```bash
php test_api.php
```

## Configuration
API settings can be configured in `config/api.php`:
- API keys
- Rate limiting
- CORS settings
- Response defaults
- Logging options
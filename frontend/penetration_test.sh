#!/bin/bash

# Penetration Testing Script for Green Resources CMS
# This script performs basic penetration tests

BASE_URL="http://localhost:8000"
ADMIN_URL="${BASE_URL}/admin"
LOG_FILE="penetration_test_results.txt"

echo "=== Penetration Test Results ===" > $LOG_FILE
echo "Date: $(date)" >> $LOG_FILE
echo "" >> $LOG_FILE

# Test 1: SQL Injection in Login Form
echo "TEST 1: SQL Injection in Login Form" >> $LOG_FILE
echo "-----------------------------------" >> $LOG_FILE
SQL_PAYLOADS=(
    "' OR '1'='1"
    "admin'--"
    "' OR 1=1--"
    "admin'/*"
)

for payload in "${SQL_PAYLOADS[@]}"; do
    echo "Testing payload: $payload" >> $LOG_FILE
    response=$(curl -s -X POST "${ADMIN_URL}/login" \
        -d "email=${payload}&password=test" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -w "%{http_code}")
    
    if echo "$response" | grep -q "dashboard\|success"; then
        echo "  ❌ VULNERABLE: SQL injection successful with payload: $payload" >> $LOG_FILE
    else
        echo "  ✅ SAFE: SQL injection prevented" >> $LOG_FILE
    fi
done
echo "" >> $LOG_FILE

# Test 2: XSS in Contact Form
echo "TEST 2: XSS in Contact Form" >> $LOG_FILE
echo "---------------------------" >> $LOG_FILE
XSS_PAYLOADS=(
    "<script>alert('XSS')</script>"
    "<img src=x onerror=alert('XSS')>"
    "<svg/onload=alert('XSS')>"
    "javascript:alert('XSS')"
)

for payload in "${XSS_PAYLOADS[@]}"; do
    echo "Testing payload: $payload" >> $LOG_FILE
    response=$(curl -s -X POST "${BASE_URL}/contact" \
        -d "name=${payload}&email=test@test.com&subject=test&message=test" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -w "%{http_code}")
    
    if echo "$response" | grep -q "<script>"; then
        echo "  ❌ VULNERABLE: XSS payload reflected" >> $LOG_FILE
    else
        echo "  ✅ SAFE: XSS payload escaped" >> $LOG_FILE
    fi
done
echo "" >> $LOG_FILE

# Test 3: CSRF Protection
echo "TEST 3: CSRF Protection" >> $LOG_FILE
echo "----------------------" >> $LOG_FILE
response=$(curl -s -X POST "${BASE_URL}/contact" \
    -d "name=test&email=test@test.com&subject=test&message=test" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -w "%{http_code}")

if echo "$response" | grep -q "419\|csrf\|token"; then
    echo "  ✅ SAFE: CSRF protection enabled" >> $LOG_FILE
else
    echo "  ❌ VULNERABLE: CSRF protection may be missing" >> $LOG_FILE
fi
echo "" >> $LOG_FILE

# Test 4: Authentication Bypass - Direct Access
echo "TEST 4: Authentication Bypass" >> $LOG_FILE
echo "----------------------------" >> $LOG_FILE
response=$(curl -s -o /dev/null -w "%{http_code}" "${ADMIN_URL}/dashboard")

if [ "$response" == "302" ] || [ "$response" == "401" ]; then
    echo "  ✅ SAFE: Admin dashboard protected" >> $LOG_FILE
else
    echo "  ❌ VULNERABLE: Admin dashboard accessible without authentication" >> $LOG_FILE
fi
echo "" >> $LOG_FILE

# Test 5: Directory Traversal
echo "TEST 5: Directory Traversal" >> $LOG_FILE
echo "--------------------------" >> $LOG_FILE
TRAVERSAL_PAYLOADS=(
    "../../../etc/passwd"
    "..\\..\\..\\windows\\system32\\config\\sam"
    "....//....//....//etc/passwd"
)

for payload in "${TRAVERSAL_PAYLOADS[@]}"; do
    response=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/${payload}")
    if [ "$response" == "200" ]; then
        echo "  ❌ VULNERABLE: Directory traversal possible with: $payload" >> $LOG_FILE
    else
        echo "  ✅ SAFE: Directory traversal prevented" >> $LOG_FILE
    fi
done
echo "" >> $LOG_FILE

# Test 6: File Upload Security
echo "TEST 6: File Upload Security" >> $LOG_FILE
echo "--------------------------" >> $LOG_FILE
echo "  ⚠️  Manual testing required for file upload" >> $LOG_FILE
echo "  Check: PHP file upload, executable file upload" >> $LOG_FILE
echo "" >> $LOG_FILE

# Test 7: Information Disclosure
echo "TEST 7: Information Disclosure" >> $LOG_FILE
echo "-----------------------------" >> $LOG_FILE
response=$(curl -s "${BASE_URL}/.env")
if echo "$response" | grep -q "APP_KEY\|DB_PASSWORD"; then
    echo "  ❌ VULNERABLE: .env file accessible" >> $LOG_FILE
else
    echo "  ✅ SAFE: .env file protected" >> $LOG_FILE
fi

response=$(curl -s "${BASE_URL}/storage/logs/laravel.log")
if echo "$response" | grep -q "SQL\|Exception\|Error"; then
    echo "  ❌ VULNERABLE: Log files accessible" >> $LOG_FILE
else
    echo "  ✅ SAFE: Log files protected" >> $LOG_FILE
fi
echo "" >> $LOG_FILE

echo "=== Test Complete ===" >> $LOG_FILE
cat $LOG_FILE


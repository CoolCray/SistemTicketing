const API_BASE = 'http://localhost:8000/api';

const endpoints = [
    '/packages',
    '/seats',
    '/additionals',
    '/transactions',
];

async function checkEndpoints() {
    console.log('--- Starting API Health Check ---');
    let allPassed = true;
    for (const endpoint of endpoints) {
        try {
            const res = await fetch(`${API_BASE}${endpoint}`);
            const data = await res.json();
            const count = data.data ? data.data.length : data.length || Object.keys(data).length || 0;
            console.log(`✅ [OK] ${endpoint} (Status: ${res.status}) - Data count: ${count}`);
        } catch (error) {
            console.error(`❌ [FAIL] ${endpoint} - ${error.message}`);
            allPassed = false;
        }
    }
    console.log('--- API Check Complete ---');
    return allPassed;
}

checkEndpoints();

# 🧪 Testing Documentation - Quick Reference

Welcome to the Thesis Management System testing suite!

---

## 📚 Documentation Overview

This folder contains **comprehensive testing documentation** for the Skripsi Management System:

### Core Documents

| Document | Purpose | Use When |
|----------|---------|----------|
| **TESTING_SUMMARY.md** | Master overview | Starting testing or need big picture |
| **TESTING_PLAN.md** | Detailed test cases (150+) | Planning test execution |
| **TEST_EXECUTION_GUIDE.md** | Step-by-step instructions | Executing tests manually |
| **MANUAL_TESTING_CHECKLIST.md** | Printable checklist | Doing hands-on testing |
| **TEST_RESULTS.md** | Results tracking | Recording test outcomes |
| **README_TESTING.md** | This file | Quick reference |

### Code Files

| File | Purpose | Use When |
|------|---------|----------|
| **tests/Feature/ThesisWorkflowTest.php** | Automated tests | Running PHPUnit tests |
| **tests/Browser/ThesisFormTest.php** | Browser tests (Dusk) | Testing UI interactions |
| **database/seeders/TestDataSeeder.php** | Test data | Setting up test environment |
| **RUN_TESTS.bat** / **.sh** | Quick test runner | Want easy menu-driven testing |

---

## 🚀 Quick Start (3 Steps)

### 1. Setup Test Environment
```bash
# Windows
RUN_TESTS.bat

# Linux/Mac
./RUN_TESTS.sh

# Choose option [1] - Setup Test Environment
```

OR manually:
```bash
php artisan migrate:fresh
php artisan db:seed --class=TestDataSeeder
php artisan storage:link
```

### 2. Run Tests

**Option A: Automated (Fast - 5 min)**
```bash
php artisan test --filter=ThesisWorkflowTest
```

**Option B: Manual (Thorough - 4-6 hours)**
1. `php artisan serve`
2. Open `MANUAL_TESTING_CHECKLIST.md`
3. Follow checklist step-by-step
4. Document results

**Option C: Use Test Runner Menu**
```bash
RUN_TESTS.bat    # Windows
./RUN_TESTS.sh   # Linux/Mac
```

### 3. Review Results
- Check console output (automated tests)
- Review `TEST_RESULTS.md` (manual tests)
- Take screenshots of critical workflows
- Document any issues found

---

## 📋 Test Accounts

Created automatically by `TestDataSeeder`:

### Administrator
- **Email:** admin@test.com
- **Password:** password

### Dosen (3 accounts)
- **Dosen 1:** dosen1@test.com / password (Psikologi Klinis)
- **Dosen 2:** dosen2@test.com / password (Psikologi Pendidikan)
- **Dosen 3:** dosen3@test.com / password (Psikologi Sosial)

### Mahasiswa (3 accounts)
- **Mahasiswa 1:** mahasiswa1@test.com / password (Andi Pratama - 2019010001)
- **Mahasiswa 2:** mahasiswa2@test.com / password (Dewi Lestari - 2019010002)
- **Mahasiswa 3:** mahasiswa3@test.com / password (Candra Kusuma - 2019010003)

---

## 🎯 Testing Scope

### Workflows Tested
1. **Skripsi Reguler** (10 stages)
   - Registration → Verification → Pembimbing → Review → Seminar → Defense → Scoring
   
2. **Skripsi MBKM** (11 stages)
   - MBKM Registration → Verification → Pembimbing → MBKM Seminar → Defense → Scoring

### Features Tested
- ✅ 17 Complete Features (all from PROSES_ALUR_SKRIPSI.md)
- ✅ Timeline Component
- ✅ File Uploads (PDF validation, size limits)
- ✅ Admin Dashboard & Monitoring
- ✅ Dosen Features (Review, Scoring)
- ✅ Security & Permissions
- ✅ Responsive Design
- ✅ Browser Compatibility

### Total Test Cases: **150+**

---

## 📊 Test Coverage

| Category | Coverage | Test Type |
|----------|----------|-----------|
| Critical Features | 100% | Automated + Manual |
| UI/UX | 100% | Manual |
| Security | 100% | Automated + Manual |
| Responsive | 80% | Manual |
| Browser Compatibility | 75% | Manual |
| Performance | 50% | Manual |

**Overall Coverage:** ~95%

---

## 🐛 Bug Reporting

Found a bug? Document it in `TEST_RESULTS.md` using this format:

```markdown
### Issue #X: [Brief Description]

**Test Case ID:** TC-SR-XXX
**Severity:** Critical / High / Medium / Low

**Steps to Reproduce:**
1. Login as mahasiswa1@test.com
2. Navigate to ...
3. Click ...

**Expected:** Success message
**Actual:** Error 500

**Screenshot:** bug-001-form-error.png
```

---

## ✅ Testing Checklist

Before declaring "testing complete":

- [ ] All automated tests pass
- [ ] All critical manual tests executed
- [ ] Screenshots captured (minimum 14)
- [ ] Issues documented
- [ ] Security tests passed
- [ ] Responsive design verified
- [ ] Cross-browser tested
- [ ] Test results documented in TEST_RESULTS.md
- [ ] Sign-off obtained

---

## 📞 Common Questions

**Q: How long does testing take?**
A: Automated (5-10 min), Manual (4-6 hours), Hybrid (2-3 hours)

**Q: Can I skip automated tests?**
A: Not recommended. Automated tests catch regressions quickly.

**Q: What browsers should I test?**
A: Chrome (primary), Firefox, Edge. Safari if available.

**Q: What if a test fails?**
A: Document in TEST_RESULTS.md, assign priority, fix if critical.

**Q: Do I need Laravel Dusk?**
A: Optional. Feature tests cover most scenarios without Dusk.

**Q: How do I prepare test files?**
A: Create PDFs of various sizes (1MB, 2MB, 10MB, 15MB, 25MB). Use any PDF generator.

**Q: Can I test on production?**
A: NO! Always test on development/staging environment.

**Q: What PHP version is required?**
A: PHP 8.1+ (Laravel 10 requirement)

---

## 🎉 Ready to Test!

### Recommended Testing Flow

**Day 1 (Setup + Smoke Test)**
1. Run `RUN_TESTS.bat` → Option [4] (Full Test Setup)
2. Review automated test results
3. Fix any critical failures

**Day 2 (Manual Testing - Critical Path)**
1. Start server: `php artisan serve`
2. Open `MANUAL_TESTING_CHECKLIST.md`
3. Test Skripsi Reguler workflow (TC-SR-001 to TC-SR-100)
4. Take screenshots
5. Document results

**Day 3 (Manual Testing - Additional Features)**
1. Test MBKM workflow
2. Test Timeline component
3. Test Application Reports
4. Test Admin/Dosen dashboards
5. Complete TEST_RESULTS.md

**Day 4 (Cross-cutting Tests)**
1. Security testing
2. Responsive design
3. Browser compatibility
4. Performance testing

**Day 5 (Bug Fixes & Retest)**
1. Review TEST_RESULTS.md
2. Fix critical issues
3. Retest failed cases
4. Final sign-off

---

## 📖 Additional Resources

### Documentation Files
- `PROSES_ALUR_SKRIPSI.md` - Complete workflow specification
- `FINAL_COMPLETION_REPORT.md` - Development completion report
- `THESIS_SYSTEM_MASTER_PLAN.md` - Original development plan

### Laravel Resources
- [Laravel Testing](https://laravel.com/docs/10.x/testing)
- [Laravel Dusk](https://laravel.com/docs/10.x/dusk)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

---

## 💡 Pro Tips

1. **Use Browser Dev Tools:** F12 to inspect errors
2. **Check Laravel Logs:** `storage/logs/laravel.log`
3. **Clear Cache Often:** `php artisan cache:clear`
4. **Use Incognito Mode:** Avoid cached assets
5. **Take Screenshots:** Visual proof of issues
6. **Test Incrementally:** Don't skip steps
7. **Document Everything:** Future you will thank you

---

## 🎯 Success Criteria

Testing is **COMPLETE** when:
- ✅ 95%+ test pass rate
- ✅ All critical bugs fixed
- ✅ Security tests passed
- ✅ Documentation updated
- ✅ Sign-off obtained
- ✅ Ready for UAT/Production

---

## 🚀 Let's Test!

**Next Step:** Run `RUN_TESTS.bat` (Windows) or `./RUN_TESTS.sh` (Linux/Mac)

**Questions?** Check TESTING_SUMMARY.md for detailed guidance.

**Happy Testing! 🎉**

---

*Last Updated: 2026-05-09*  
*Version: 1.0*  
*Laravel: 10.48.28*

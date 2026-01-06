const form = document.getElementById('studentForm')
const studentName = document.getElementById('studentName')
const rollNumber = document.getElementById('rollNumber')
const email = document.getElementById('email')
const courseName = document.getElementById('courseName')
const submitBtn = document.getElementById('submitBtn')
const loadingMsg = document.getElementById('loadingMsg')

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

;[studentName, rollNumber, email, courseName].forEach(f =>
  f.addEventListener('input', () => clearError(f))
)

form.addEventListener('submit', e => {
  e.preventDefault()
  clearAllErrors()

  let valid = true

  if (!studentName.value.trim()) {
    showError(studentName, 'nameError')
    valid = false
  }

  if (!rollNumber.value.trim()) {
    showError(rollNumber, 'rollError')
    valid = false
  }

  if (!emailRegex.test(email.value.trim())) {
    showError(email, 'emailError')
    valid = false
  }

  if (!courseName.value) {
    showError(courseName, 'courseError')
    valid = false
  }

  if (!valid) {
    alert('Please fill all fields correctly')
    return
  }

  submitBtn.disabled = true
  loadingMsg.classList.add('show')

  fetch('http://localhost/student-registration/submit_student.php', {
    method: 'POST',
    body: new FormData(form)
  })
    .then(r => r.json())
    .then(d => {
      submitBtn.disabled = false
      loadingMsg.classList.remove('show')

      if (d.status === 'success') {
        alert('Student registered successfully')
        form.reset()
      } else {
        alert(d.message)
      }
    })
    .catch(() => {
      submitBtn.disabled = false
      loadingMsg.classList.remove('show')
      alert('Server error')
    })
})

function showError (field, id) {
  field.classList.add('error')
  document.getElementById(id).classList.add('show')
}

function clearError (field) {
  field.classList.remove('error')
  const map = {
    studentName: 'nameError',
    rollNumber: 'rollError',
    email: 'emailError',
    courseName: 'courseError'
  }
  document.getElementById(map[field.id]).classList.remove('show')
}

function clearAllErrors () {
  ;[studentName, rollNumber, email, courseName].forEach(f =>
    f.classList.remove('error')
  )
  document
    .querySelectorAll('.error-message')
    .forEach(e => e.classList.remove('show'))
}

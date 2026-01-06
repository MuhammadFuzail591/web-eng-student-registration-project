const form = document.getElementById('studentForm')
const studentName = document.getElementById('studentName')
const rollNumber = document.getElementById('rollNumber')
const email = document.getElementById('email')
const courseName = document.getElementById('courseName')
const alertBox = document.getElementById('alertBox')
const submitBtn = document.getElementById('submitBtn')
const loadingMsg = document.getElementById('loadingMsg')

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

;[studentName, rollNumber, email, courseName].forEach(field => {
  field.addEventListener('input', () => {
    clearError(field)
  })
})

form.addEventListener('submit', function (e) {
  e.preventDefault()

  clearAllErrors()

  let isValid = true

  if (studentName.value.trim() === '') {
    showError(studentName, 'nameError')
    isValid = false
  }

  if (rollNumber.value.trim() === '') {
    showError(rollNumber, 'rollError')
    isValid = false
  }

  if (email.value.trim() === '') {
    showError(email, 'emailError')
    document.getElementById('emailError').textContent =
      'Please enter email address'
    isValid = false
  } else if (!emailRegex.test(email.value.trim())) {
    showError(email, 'emailError')
    document.getElementById('emailError').textContent =
      'Please enter a valid email'
    isValid = false
  }

  if (courseName.value === '') {
    showError(courseName, 'courseError')
    isValid = false
  }

  if (isValid) {
    submitBtn.disabled = true
    loadingMsg.classList.add('show')
    hideAlert()

    const formData = new FormData(form)

    fetch('submit_student.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        submitBtn.disabled = false
        loadingMsg.classList.remove('show')

        if (data.status === 'success') {
          showAlert(
            'success',
            data.message +
              ' - ' +
              data.data.studentName +
              ' registered successfully!'
          )

          setTimeout(() => {
            form.reset()
            hideAlert()
          }, 3000)
        } else {
          showAlert(
            'error',
            data.message + (data.errors ? ': ' + data.errors.join(', ') : '')
          )
          setTimeout(() => {
            hideAlert()
          }, 4000)
        }
      })
      .catch(error => {
        submitBtn.disabled = false
        loadingMsg.classList.remove('show')

        console.error('Error:', error)
        showAlert(
          'error',
          'Connection error. Please make sure the PHP server is running.'
        )
        setTimeout(() => {
          hideAlert()
        }, 4000)
      })
  } else {
    showAlert('error', 'Please fill in all required fields correctly.')
    setTimeout(() => {
      hideAlert()
    }, 3000)
  }
})

function showError (field, errorId) {
  field.classList.add('error')
  document.getElementById(errorId).classList.add('show')
}

function clearError (field) {
  field.classList.remove('error')
  if (field.id === 'studentName') {
    document.getElementById('nameError').classList.remove('show')
  } else if (field.id === 'rollNumber') {
    document.getElementById('rollError').classList.remove('show')
  } else if (field.id === 'email') {
    document.getElementById('emailError').classList.remove('show')
  } else if (field.id === 'courseName') {
    document.getElementById('courseError').classList.remove('show')
  }
}

function clearAllErrors () {
  ;[studentName, rollNumber, email, courseName].forEach(field => {
    field.classList.remove('error')
  })
  document.querySelectorAll('.error-message').forEach(msg => {
    msg.classList.remove('show')
  })
}

function showAlert (type, message) {
  alertBox.textContent = message
  alertBox.className = 'alert show alert-' + type
}

function hideAlert () {
  alertBox.classList.remove('show')
}

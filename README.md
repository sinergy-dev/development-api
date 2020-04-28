# development-api

Dibawah ini akan di descripsikan cara penggunannya

Dashboard Activity
dashboard/getDashboard [GET]
  Parameter
    - id_user: int (mandatory)
    
dashboard/getJobCategory [GET]

dashboard/getJobList[GET]

dashboard/getJobListSumary[GET]
  Parameter
    - id_job: int (mandatory)
    
dashboard/getJobListRecomended[GET]
  Parameter
    - id_engineer: int (mandatory)

Job Activity

job/getJobOpen [GET]
  Parameter
    - id_job: int (mandatory)
job/getJobProgress [GET]
  Parameter
    - id_job: int (mandatory
  Return
    - Job(Object)
    - Progress(Array)

Payment Activity

payment/getJobPayment [GET]
  Parameter
    - id_engineer: int (mandatory)
    
payment/getJobPaymentDetail [GET]
  Parameter
    - id_payment: int (mandatory)

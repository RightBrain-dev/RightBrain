import Validator from 'validator'

export const loginValidations=(data)=>
{
 //console.log(data);
const error={};
if(!Validator.isEmail(data.userEmail))error.email="Invalid Email";
if(Validator.isEmpty(data.userPassword))error.password="Can't Be Blank"

return error
}


export const signupValidations=(data)=>
{
const error={};
if(!Validator.isByteLength(data.name,{min:3,max:20}))error.name="Name length should be greater than 3"
if(!Validator.isEmail(data.email))error.email="Invalid Email";
if(!Validator.isNumeric(data.mobileNumber))error.mobile="Enter valid Mobile Number"
if(!Validator.isByteLength(data.mobileNumber,[{min:10,max:10}]))error.mobile="Enter valid Mobile Number"
if(Validator.isEmpty(data.password))error.password="Can't Be Blank"
if(!Validator.equals(data.password,data.repassword))error.repassword="password must be match"
return error
}

export const add_material_form_validations=data=>{
const error={};
if(Validator.isEmpty(data.name))error.name="Please select Material"
if(Validator.isEmpty(data.dcnumber))error.dcnumber="Cant BE Blank"
if(Validator.isEmpty(data.quantity))error.quantity="Can't Be Blank"
if(!Validator.isNumeric(data.quantity))error.quantity="Quantity should be number"
if(Validator.isEmpty(data.dealerName))error.dealerName="dealer Name Require"
if(Validator.isEmpty(data.dealerBillDate))error.dealerBillDate="dealer Name Require"
if(Validator.isDate(data.dealerBillDate,"dd-mm-yyyy"))error.dealerBillDate="please select date"
return error
}

export const add_dropdown_material_validations=data=>{
  const error={}
  if(Validator.isEmpty(data.materialName))error.materialName="Can't BE Blank"
  if(Validator.isEmpty(data.materialUnit))error.materialUnit="Can't BE Blank"
  return error
}

export const ResetPasswordValidations=data=>{
  const error={}
  if(Validator.isEmpty(data.password))error.password="Can't BE Blank"
  if(Validator.isEmpty(data.repassword))error.repassword="Can't BE Blank"
  if(!Validator.equals(data.password,data.repassword))error.repassword="password Must be match"
  return error
}
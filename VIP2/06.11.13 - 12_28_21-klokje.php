<?php exit() ?>--by klokje 173.245.52.73
function OnLoad()
	print("Name Protection test")
	if type(GetUser) == "function" then 
		if debug.getinfo(GetUser, "S").what == "C" then 
			print("Succes")
		else 
			print("Name change")
		end 

	else 
		print("Name change")
	end 
end
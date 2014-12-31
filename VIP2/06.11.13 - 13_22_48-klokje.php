<?php exit() ?>--by klokje 173.245.52.73
old = _G.GetAsyncWebResult
function GetAsyncWebResult2(a, b, c, d)
print ( a )
old(a,b,c,d)
end
_G.GetUser= GetAsyncWebResult2


function OnLoad()
	print("Name Protection test")
	
	if ProtectedApi(_G.GetUser) then 
		print("it is protected")
	end 
	print(debug.getinfo(ProtectedApi, "S"))
end

function ProtectedApi(funct)
	if type(funct) == "function" then 
		print(debug.getinfo(funct, "S").what)
		if debug.getinfo(funct, "S").what == "C" then 
			return true 
		else 
			return false
		end 

	else 
		return false 
	end 
end 
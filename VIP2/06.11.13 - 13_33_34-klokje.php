<?php exit() ?>--by klokje 173.245.52.73
function OnLoad()
	if debug.getinfo(OnLoad, "S").linedefined ~= 1 then print("HACKER") return  end 

	print("Name Protection test")

	if debug.getinfo(ProtectedApi, "S").linedefined == 17 and ProtectedApi(_G.GetUser) then 
		print("it is protected")
	end 
end

function ProtectedApi(funct)
	if type(funct) == "function" then 
		if debug.getinfo(funct, "S").what == "C" then return true 
		else return false end 
	else 
		return false 
	end 
end
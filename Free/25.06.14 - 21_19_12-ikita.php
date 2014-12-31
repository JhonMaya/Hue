<?php exit() ?>--by ikita 61.238.99.170
local wayPointManager = WayPointManager()

wpsTime = {0,0,0,0,0,0,0,0,0,0}
lastDestx = {}
lastDestz = {}
suspectUpdate = {}
for i = 1, 10 , 1 do
	suspectUpdate[i] = {}
end

susList = {false,false,false,false,false,false,false,false,false,false}
function OnLoad()
	print("<font color='#b1a1c6'>ikita's BoL Friend Finder started. The process would take a while. Click menu to show results.</font>")
	BolFinderConfig = scriptConfig("BoL Friend Finder", "bol_friend_finder_")
	BolFinderConfig:addSubMenu("Find Friend Now", "ffriend")
	BolFinderConfig.ffriend:addParam("ffstart", "Show Friend Finder results", SCRIPT_PARAM_ONOFF, false)
	BolFinderConfig.ffriend.ffstart = false
end

function OnTick()
	if BolFinderConfig.ffriend.ffstart == true then
		local listed = false
		for i = 1, heroManager.iCount do
			local hero = heroManager:GetHero(i)
			if susList[i] == true then
				listed = true
				print("<font color='#b1a1c6'>Found " .. hero.charName .. ", " .. hero.name .. "</font>")
			end
		end
		BolFinderConfig.ffriend.ffstart = false
		if listed == false then
			print("<font color='#b1a1c6'>No Friends found.. yet</font>")
		end
	end
end

function checkAverage()
	for i = 1, heroManager.iCount, 1 do
		if #suspectUpdate[i] > 15 then
			suspectUpdate[i] = {}
			return
		end
		if #suspectUpdate[i] == 15 then
			local temp = 0
			for j = 1, #suspectUpdate[i], 1 do
				temp = temp + suspectUpdate[i][j]
			end
			if temp/#suspectUpdate[i] < 90 then
				susList[i] = true
			end
		end
	end
end

function OnDraw()
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		local point = wayPointManager:GetWayPoints(hero)		
		local maxp = 1
		for i, wp in ipairs(point) do maxp = i end
		if lastDestx[i] == nil then
			lastDestx[i] = point[maxp].x
			lastDestz[i] = point[maxp].z
			wpsTime[i] = GetTickCount()
		end
		if wpsTime[i] ~= nil and lastDestx[i] ~= nil and point[maxp].x ~= lastDestx[i] then
				table.insert(suspectUpdate[i],GetTickCount() - wpsTime[i])
				checkAverage()
			lastDestx[i] = point[maxp].x
			lastDestz[i] = point[maxp].z
			wpsTime[i] = GetTickCount()
		end
	end
end
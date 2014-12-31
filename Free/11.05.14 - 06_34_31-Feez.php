<?php exit() ?>--by Feez 24.14.208.12
if not target and ts.target ~= nil then target = ts.target end
	if not target then return end
	local enemyCount = 0
	local points = {}
	for i=1, heroManager.iCount do
		local enemy = heroManager:getHero(i)
		if enemy.team == TEAM_ENEMY and ValidTarget(enemy, 800) then
			enemyCount = enemyCount + 1
		end
	end
	if enemyCount == 1 then
		Position = ProdQ:GetPrediction(target)
		table.insert(points, Position)
		for seed, object in pairs(seeds) do
			if object ~= nil and GetDistanceSqr(ts.target.visionPos, object) <= 193600 and not object.isUpgraded then table.insert(points, seeds[seed]) end
		end

		local mec = MEC(points)
		local finalCastPos = mec:Compute()
		if finalCastPos ~= nil then
			CastSpell(_Q, finalCastPos.center.x, finalCastPos.center.z)
		end
	end

	local CastPosition = ProdQ:GetPrediction(target)
	if CastPosition ~= nil then CastSpell(_Q, CastPosition.x, CastPosition.z) end